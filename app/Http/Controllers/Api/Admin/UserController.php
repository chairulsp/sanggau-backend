<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::select('id', 'name', 'email', 'role', 'kecamatan', 'created_at')->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:superadmin,admin,editor,penulis',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'kecamatan' => $request->kecamatan,
        ]);

        return response()->json(['message' => 'Pengguna berhasil dibuat.', 'data' => $user->only(['id','name','email','role','kecamatan'])], 201);
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id)->only(['id','name','email','role','kecamatan','created_at']));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'email' => 'email|unique:users,email,' . $id,
            'role'  => 'in:superadmin,admin,editor,penulis',
        ]);

        $data = $request->only(['name', 'email', 'role', 'kecamatan']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json(['message' => 'Pengguna berhasil diperbarui.', 'data' => $user->only(['id','name','email','role','kecamatan'])]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') {
            return response()->json(['message' => 'Tidak bisa menghapus superadmin.'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus.']);
    }
}
