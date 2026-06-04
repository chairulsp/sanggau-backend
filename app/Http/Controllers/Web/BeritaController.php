<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::where('aktif', true)
            ->orderByDesc('created_at')
            ->paginate(12);

        $kategori = Berita::where('aktif', true)
            ->distinct()->pluck('kategori')->filter()->values();

        return view('web.berita.index', compact('berita', 'kategori'));
    }

    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('aktif', true)
            ->firstOrFail();

        // Tambah views
        $berita->increment('views_count');

        $related = Berita::where('aktif', true)
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->latest()->take(4)->get();

        return view('web.berita.show', compact('berita', 'related'));
    }
}
