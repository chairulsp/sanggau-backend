<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Kita juga bisa mencatat percobaan gagal jika user ditemukan
            if ($user) {
                $uaInfo = LoginHistory::parseUserAgent($request->userAgent());
                LoginHistory::create([
                    'user_id' => $user->id,
                    'email' => $request->email,
                    'ip_address' => $request->ip(),
                    'browser' => $uaInfo['browser'],
                    'device' => $uaInfo['device'],
                    'os' => $uaInfo['os'],
                    'status' => 'Gagal (Password Salah)',
                ]);
            }
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Catat riwayat login berhasil
        $uaInfo = LoginHistory::parseUserAgent($request->userAgent());
        LoginHistory::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'browser' => $uaInfo['browser'],
            'device' => $uaInfo['device'],
            'os' => $uaInfo['os'],
            'status' => 'Berhasil Login',
        ]);

        // Hapus token lama jika diinginkan (opsional)
        // $user->tokens()->delete();

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout'
        ]);
    }
}
