<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::where('aktif', true)->orderBy('urutan')->get();
        $kategoriList = $layanan->pluck('kategori')->unique()->filter()->prepend('Semua')->values();
        return view('web.layanan.index', compact('layanan','kategoriList'));
    }
}
