<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Layanan;
use App\Models\Skpd;
use App\Models\Banner;
use App\Models\Pengumuman;
use App\Models\Statistik;
use App\Models\Galeri;
use App\Models\ProfilDiskominfo;
use App\Models\StrukturOrganisasi;
use App\Models\Dokumen;
use App\Models\Ppid;
use App\Models\GaleriVideo;
use App\Models\Pengaduan;
use App\Models\Laman;
use App\Models\Setting;

class PublicController extends Controller
{
    public function berita(Request $request)
    {
        $q = Berita::orderBy('published_at', 'desc');
        if ($request->kategori) $q->where('kategori', $request->kategori);
        if ($request->search)   $q->where('judul', 'like', '%'.$request->search.'%');
        return response()->json($q->paginate($request->get('per_page', 12)));
    }

    public function detailBerita($slug)
    {
        $berita = Berita::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        $related = Berita::where('id', '!=', $berita->id)
            ->where('kategori', $berita->kategori)
            ->orderBy('published_at', 'desc')
            ->limit(3)->get();
        return response()->json(['berita' => $berita, 'terkait' => $related]);
    }

    public function agenda()
    {
        return response()->json(Agenda::orderBy('tanggal_mulai')->get());
    }

    public function layanan()
    {
        $layanans = Layanan::orderBy('urutan')->get();
        $mapped = $layanans->map(function ($l) {
            return [
                'id' => $l->id,
                'judul' => $l->nama,
                'nama' => $l->nama,
                'deskripsi' => $l->deskripsi,
                'icon' => $l->ikon,
                'ikon' => $l->ikon,
                'url' => $l->link,
                'link' => $l->link,
                'warna' => $l->warna,
                'kategori' => $l->kategori,
                'urutan' => $l->urutan,
                'aktif' => $l->aktif,
                'is_active' => (bool)$l->aktif,
            ];
        });
        return response()->json(['data' => $mapped]);
    }

    public function skpd()
    {
        return response()->json(Skpd::orderBy('nama')->get());
    }

    public function banner()
    {
        return response()->json(Banner::orderBy('urutan')->get());
    }

    public function pengumuman()
    {
        return response()->json(Pengumuman::orderBy('penting', 'desc')->orderBy('tanggal_mulai', 'desc')->get());
    }

    public function statistik()
    {
        return response()->json(Statistik::orderBy('urutan')->get());
    }

    public function galeri()
    {
        return response()->json(Galeri::orderBy('created_at', 'desc')->get());
    }

    // ==================== NEW DISKOMINFO ====================

    public function profilDiskominfo()
    {
        return response()->json(ProfilDiskominfo::first());
    }

    public function strukturOrganisasi()
    {
        return response()->json(StrukturOrganisasi::where('aktif', true)->orderBy('urutan')->get());
    }

    public function dokumen(Request $request)
    {
        $q = Dokumen::where('aktif', true)->orderBy('created_at', 'desc');
        if ($request->kategori) $q->where('kategori', $request->kategori);
        return response()->json($q->get());
    }

    public function ppid(Request $request)
    {
        $q = Ppid::where('aktif', true)->orderBy('urutan');
        if ($request->kategori) $q->where('kategori', $request->kategori);
        return response()->json($q->get());
    }

    public function video()
    {
        return response()->json(GaleriVideo::where('aktif', true)->orderBy('urutan')->get());
    }

    public function pengaduan(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email',
            'telepon' => 'nullable|string',
            'subjek'  => 'required|string|max:255',
            'pesan'   => 'required|string',
        ]);

        $pengaduan = Pengaduan::create($request->only(['nama', 'email', 'telepon', 'subjek', 'pesan']));
        return response()->json(['message' => 'Pengaduan Anda berhasil dikirim. Terima kasih.', 'data' => $pengaduan], 201);
    }

    public function semuaLaman()
    {
        return response()->json(Laman::where('aktif', true)->orderBy('judul')->get());
    }

    public function laman($slug)
    {
        $laman = Laman::where('slug', $slug)->where('aktif', true)->firstOrFail();
        return response()->json($laman);
    }

    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function hoaks()
    {
        try {
            $response = Http::timeout(3)->get('https://turnbackhoax.id/feed/');
            
            if (!$response->successful()) {
                return response()->json($this->getMockHoaks());
            }

            $xml = @simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
            $items = [];
            $count = 0;
            if ($xml && isset($xml->channel->item)) {
                foreach ($xml->channel->item as $item) {
                    if ($count >= 5) break;
                    $title = (string)$item->title;
                    $status = 'HOAKS';
                    if (stripos($title, 'disinformasi') !== false) $status = 'DISINFORMASI';
                    elseif (stripos($title, 'benar') !== false || stripos($title, 'fakta') !== false) $status = 'FAKTA';
                    
                    $items[] = [
                        'id' => md5((string)$item->link),
                        'judul' => $title,
                        'status' => $status,
                        'tanggal' => date('d M Y', strtotime((string)$item->pubDate)),
                        'link' => (string)$item->link
                    ];
                    $count++;
                }
            }
            return response()->json($items ?: $this->getMockHoaks());
        } catch (\Exception $e) {
            return response()->json($this->getMockHoaks());
        }
    }

    private function getMockHoaks()
    {
        return [
            ['id' => '1', 'judul' => '[HOAKS] Bantuan Sosial Tunai Rp2 Juta dari Pemkab Sanggau', 'status' => 'HOAKS', 'tanggal' => '10 Mei 2026', 'link' => '#'],
            ['id' => '2', 'judul' => '[DISINFORMASI] Pemadaman Listrik Total Selama Seminggu', 'status' => 'DISINFORMASI', 'tanggal' => '08 Mei 2026', 'link' => '#'],
            ['id' => '3', 'judul' => '[HOAKS] Pendaftaran CPNS Jalur Khusus Tanpa Tes', 'status' => 'HOAKS', 'tanggal' => '05 Mei 2026', 'link' => '#']
        ];
    }
}
