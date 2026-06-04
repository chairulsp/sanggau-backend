<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log successful login
            $uaInfo = LoginHistory::parseUserAgent($request->userAgent());
            LoginHistory::create([
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip_address' => $request->ip(),
                'browser' => $uaInfo['browser'],
                'device' => $uaInfo['device'],
                'os' => $uaInfo['os'],
                'status' => 'Berhasil Login (Web CMS)',
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        // Log failed login
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $uaInfo = LoginHistory::parseUserAgent($request->userAgent());
            LoginHistory::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'browser' => $uaInfo['browser'],
                'device' => $uaInfo['device'],
                'os' => $uaInfo['os'],
                'status' => 'Gagal Login (Web CMS - Password Salah)',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Berhasil logout.');
    }
}
