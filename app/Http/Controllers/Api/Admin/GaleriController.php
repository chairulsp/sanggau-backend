<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index()
    {
        return response()->json(Galeri::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'kategori' => 'nullable|string|max:100',
            'deskripsi'=> 'nullable|string',
            'aktif'    => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galeri'), $filename);
            $validated['gambar'] = '/uploads/galeri/' . $filename;
        } else {
            $validated['gambar'] = null;
        }

        return response()->json(Galeri::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Galeri::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);
        $validated = $request->validate([
            'judul'    => 'sometimes|required|string|max:255',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'kategori' => 'nullable|string|max:100',
            'deskripsi'=> 'nullable|string',
            'aktif'    => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galeri'), $filename);
            $validated['gambar'] = '/uploads/galeri/' . $filename;
        } else {
            unset($validated['gambar']);
        }
        $galeri->update($validated);
        return response()->json($galeri);
    }

    public function destroy($id)
    {
        Galeri::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
