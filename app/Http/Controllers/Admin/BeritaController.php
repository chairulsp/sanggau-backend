<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('aktif', $request->status === 'published');
        }

        $beritas = $query->latest()->paginate(15);
        $kategoris = Berita::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');

        return view('admin.berita.index', compact('beritas', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Berita::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');
        
        return view('admin.berita.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'ringkasan' => 'nullable|string|max:500',
            'kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'penulis' => 'required|string|max:255',
            'aktif' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::slug($validated['judul']) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/berita', $filename);
            $validated['gambar'] = 'berita/' . $filename;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['judul']) . '-' . time();
        
        // Set published_at
        $validated['aktif'] = $request->has('aktif') ? true : false;
        $validated['published_at'] = $validated['aktif'] ? now() : null;

        Berita::create($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        $kategoris = Berita::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');
        
        return view('admin.berita.edit', compact('berita', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'ringkasan' => 'nullable|string|max:500',
            'kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'penulis' => 'required|string|max:255',
            'aktif' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($berita->gambar) {
                Storage::delete('public/' . $berita->gambar);
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::slug($validated['judul']) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/berita', $filename);
            $validated['gambar'] = 'berita/' . $filename;
        }

        // Update slug if title changed
        if ($validated['judul'] !== $berita->judul) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . $berita->id;
        }

        // Set published_at
        $validated['aktif'] = $request->has('aktif') ? true : false;
        $validated['published_at'] = $validated['aktif'] ? now() : $berita->published_at;

        $berita->update($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Delete image
        if ($berita->gambar) {
            Storage::delete('public/' . $berita->gambar);
        }
        
        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->aktif = !$berita->aktif;
        $berita->published_at = $berita->aktif ? now() : null;
        $berita->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berita berhasil diubah!',
            'aktif' => $berita->aktif
        ]);
    }
}
