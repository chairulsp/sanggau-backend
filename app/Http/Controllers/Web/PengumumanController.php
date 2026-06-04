<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::where('aktif', true)
            ->orderByDesc('penting')
            ->orderByDesc('tanggal_mulai')
            ->paginate(10);
        return view('web.pengumuman.index', compact('pengumuman'));
    }
}
