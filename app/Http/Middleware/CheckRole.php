<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Jika user adalah superadmin, selalu izinkan
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Jika route membutuhkan role tertentu, dan role user tidak ada di daftar $roles
        if (!empty($roles) && !in_array($user->role, $roles)) {
            return response()->json(['message' => 'Akses ditolak. Peran Anda tidak memiliki izin.'], 403);
        }

        return $next($request);
    }
}
