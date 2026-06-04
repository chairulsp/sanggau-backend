<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        return response()->json(Berita::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'konten'    => 'nullable|string',
            'ringkasan' => 'nullable|string',
            'kategori'  => 'nullable|string|max:100',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'penulis'   => 'nullable|string|max:255',
            'status'    => 'nullable|in:draft,published',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $filename);
            $validated['gambar'] = '/uploads/berita/' . $filename;
        } else {
            $validated['gambar'] = null;
        }

        $validated['slug']         = Str::slug($validated['judul']) . '-' . time();
        $validated['aktif']        = ($validated['status'] ?? 'draft') === 'published';
        $validated['published_at'] = $validated['aktif'] ? now() : null;
        unset($validated['status']);

        $berita = Berita::create($validated);
        return response()->json($berita, 201);
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json($berita);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul'     => 'sometimes|required|string|max:255',
            'konten'    => 'nullable|string',
            'ringkasan' => 'nullable|string',
            'kategori'  => 'nullable|string|max:100',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'penulis'   => 'nullable|string|max:255',
            'status'    => 'nullable|in:draft,published',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $filename);
            $validated['gambar'] = '/uploads/berita/' . $filename;
        } else {
            // Remove from validated so it doesn't overwrite existing with null if not uploaded
            unset($validated['gambar']);
        }

        if (isset($validated['judul'])) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . $berita->id;
        }
        if (array_key_exists('status', $validated)) {
            $validated['aktif']        = $validated['status'] === 'published';
            $validated['published_at'] = $validated['aktif'] ? now() : null;
            unset($validated['status']);
        }

        $berita->update($validated);
        return response()->json($berita);
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();
        return response()->json(null, 204);
    }
}
