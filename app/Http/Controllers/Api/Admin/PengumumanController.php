<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        return response()->json(Pengumuman::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'konten'         => 'nullable|string',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date',
            'penting'        => 'nullable|boolean',
            'aktif'          => 'nullable|boolean',
        ]);

        $pengumuman = Pengumuman::create($validated);
        return response()->json($pengumuman, 201);
    }

    public function show($id)
    {
        return response()->json(Pengumuman::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $validated = $request->validate([
            'judul'          => 'sometimes|required|string|max:255',
            'konten'         => 'nullable|string',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date',
            'penting'        => 'nullable|boolean',
            'aktif'          => 'nullable|boolean',
        ]);

        $pengumuman->update($validated);
        return response()->json($pengumuman);
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
