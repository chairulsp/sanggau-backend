<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\Pengumuman;
use App\Models\Agenda;
use App\Models\Layanan;
use App\Models\Statistik;
use App\Models\ProfilDiskominfo;
use App\Models\Pegawai;
use App\Models\Setting;
use App\Models\Coverage4g;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Cache::remember('home.banners', 300, fn() =>
            Banner::where('aktif', true)->orderBy('urutan')->get()
        );

        $pengumuman = Cache::remember('home.pengumuman', 300, fn() =>
            Pengumuman::where('aktif', true)
                ->where(function($q) {
                    $q->whereNull('tanggal_selesai')
                      ->orWhere('tanggal_selesai', '>=', now());
                })
                ->orderByDesc('penting')
                ->orderByDesc('tanggal_mulai')
                ->take(5)->get()
        );

        $agenda = Cache::remember('home.agenda', 300, fn() =>
            Agenda::where('aktif', true)
                ->where('tanggal_mulai', '>=', now()->subDays(1))
                ->orderBy('tanggal_mulai')
                ->take(4)->get()
        );

        $berita = Cache::remember('home.berita', 300, fn() =>
            Berita::where('aktif', true)
                ->orderByDesc('created_at')
                ->take(6)->get()
        );

        $layanan = Cache::remember('home.layanan', 300, fn() =>
            \App\Models\Layanan::where('aktif', true)
                ->orderBy('urutan')->get()
        );

        $statistik = Cache::remember('home.statistik', 300, fn() =>
            Statistik::where('aktif', true)->orderBy('urutan')->get()
        );

        $profil_diskominfo = Cache::remember('home.profil_diskominfo', 600, fn() =>
            ProfilDiskominfo::first()
        );

        $pimpinan = Cache::remember('home.pimpinan', 600, fn() =>
            Pegawai::where('tipe_jabatan', 'pimpinan')
                ->where('aktif', true)
                ->orderBy('urutan')
                ->first()
        );

        $coverage = Cache::remember('home.coverage4g', 300, fn() =>
            Coverage4g::orderBy('urutan')->get()
        );

        $settings = Cache::remember('home.settings', 600, fn() =>
            Setting::pluck('value', 'key')
        );

        return view('web.home', compact(
            'banners', 'pengumuman', 'agenda', 'berita',
            'layanan', 'statistik', 'profil_diskominfo',
            'pimpinan', 'coverage', 'settings'
        ));
    }
}
