<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Ganti password user yang sedang login
     * POST /api/admin/profile/change-password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = $request->user();

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Password saat ini tidak sesuai.',
                'errors'  => ['current_password' => ['Password saat ini tidak sesuai.']],
            ], 422);
        }

        // Pastikan password baru berbeda
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'message' => 'Password baru tidak boleh sama dengan password lama.',
                'errors'  => ['new_password' => ['Password baru tidak boleh sama dengan password lama.']],
            ], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }

    /**
     * Update profil user yang sedang login
     * PUT /api/admin/profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $user->only(['id', 'name', 'email', 'role']),
        ]);
    }
}
