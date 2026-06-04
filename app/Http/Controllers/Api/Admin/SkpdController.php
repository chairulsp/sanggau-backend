<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skpd;
use Illuminate\Http\Request;

class SkpdController extends Controller
{
    public function index()
    {
        return response()->json(Skpd::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kepala' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'aktif' => 'nullable|boolean'
        ]);

        $skpd = Skpd::create($validated);
        return response()->json($skpd, 201);
    }

    public function show(Skpd $skpd)
    {
        return response()->json($skpd);
    }

    public function update(Request $request, Skpd $skpd)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kepala' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'aktif' => 'nullable|boolean'
        ]);

        $skpd->update($validated);
        return response()->json($skpd);
    }

    public function destroy(Skpd $skpd)
    {
        $skpd->delete();
        return response()->json(null, 204);
    }
}
