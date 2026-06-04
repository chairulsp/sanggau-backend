<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Dokumen;

class DownloadController extends Controller
{
    public function index()
    {
        $dokumen  = Dokumen::where('aktif', true)->orderByDesc('created_at')->paginate(15);
        $kategori = Dokumen::where('aktif', true)->distinct()->pluck('kategori')->filter()->values();
        return view('web.download.index', compact('dokumen','kategori'));
    }
}
