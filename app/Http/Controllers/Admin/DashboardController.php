<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Banner;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Agenda;
use App\Models\Pengumuman;
use App\Models\Pengaduan;
use App\Models\Dokumen;
use App\Models\User;
use App\Models\Visitor;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'berita' => Berita::count(),
            'berita_published' => Berita::where('aktif', true)->count(),
            'banner' => Banner::where('aktif', true)->count(),
            'galeri' => Galeri::count(),
            'layanan' => Layanan::count(),
            'agenda' => Agenda::count(),
            'agenda_upcoming' => Agenda::where('tanggal_mulai', '>=', now())->count(),
            'pengumuman' => Pengumuman::count(),
            'pengaduan' => Pengaduan::count(),
            'pengaduan_pending' => Pengaduan::where('status', 'pending')->count(),
            'pengaduan_process' => Pengaduan::where('status', 'diproses')->count(),
            'dokumen' => Dokumen::count(),
            'users' => User::count(),
            'visitors_today' => Visitor::whereDate('visited_at', today())->count(),
            'visitors_total' => Visitor::count(),
        ];

        // Latest berita
        $latestBerita = Berita::latest()->take(5)->get();

        // Latest pengaduan
        $latestPengaduan = Pengaduan::latest()->take(5)->get();

        // Latest login history
        $latestLogins = LoginHistory::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Visitor stats (last 7 days)
        $visitorStats = Visitor::select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('visited_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Berita stats by kategori
        $beritaByKategori = Berita::select('kategori', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestBerita',
            'latestPengaduan',
            'latestLogins',
            'visitorStats',
            'beritaByKategori'
        ));
    }
}
