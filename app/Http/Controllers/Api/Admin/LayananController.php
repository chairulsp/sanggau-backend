<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::orderBy('urutan', 'asc')->get();
        // Map to frontend-compatible fields
        $mapped = $layanans->map(function ($l) {
            return [
                'id'          => $l->id,
                'nama'        => $l->nama,
                'judul'       => $l->nama,
                'deskripsi'   => $l->deskripsi,
                'ikon'        => $l->ikon,
                'icon'        => $l->ikon,
                'link'        => $l->link,
                'url'         => $l->link,
                'warna'       => $l->warna,
                'kategori'    => $l->kategori,
                'urutan'      => $l->urutan,
                'aktif'       => $l->aktif,
                'is_active'   => (bool)$l->aktif,
                'created_at'  => $l->created_at,
                'updated_at'  => $l->updated_at,
            ];
        });
        return response()->json(['data' => $mapped]);
    }

    /**
     * Sync layanan from external API (Kabar Sanggau)
     */
    public function sync()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Accept' => 'application/json'])
                ->get('http://api.diskominfosgudev.my.id/api/v1/layanan');

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Gagal mengambil data dari API eksternal',
                    'error'   => 'HTTP ' . $response->status()
                ], 502);
            }

            $externalData = $response->json('data') ?? $response->json();
            if (!is_array($externalData)) {
                return response()->json(['message' => 'Format data API tidak valid'], 502);
            }

            $synced = 0;
            $skipped = 0;

            foreach ($externalData as $item) {
                $nama = $item['judul'] ?? $item['nama'] ?? null;
                if (!$nama) continue;

                // Check if already exists by nama
                $exists = Layanan::where('nama', $nama)->first();
                if ($exists) {
                    $skipped++;
                    continue;
                }

                Layanan::create([
                    'nama'      => $nama,
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'ikon'      => $item['icon'] ?? $item['ikon'] ?? 'fa-globe',
                    'link'      => $item['url'] ?? $item['link'] ?? null,
                    'warna'     => $item['warna'] ?? '#1A56DB',
                    'kategori'  => $item['kategori'] ?? 'umum',
                    'urutan'    => $item['urutan'] ?? 0,
                    'aktif'     => $item['is_active'] ?? true,
                ]);
                $synced++;
            }

            return response()->json([
                'message' => "Sinkronisasi berhasil! {$synced} layanan baru ditambahkan, {$skipped} sudah ada.",
                'synced'  => $synced,
                'skipped' => $skipped,
                'total'   => Layanan::count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Layanan $layanan)
    {
        $layanan->aktif = !$layanan->aktif;
        $layanan->save();
        return response()->json([
            'message'   => $layanan->aktif ? 'Layanan diaktifkan' : 'Layanan dinonaktifkan',
            'aktif'     => $layanan->aktif,
            'is_active' => (bool)$layanan->aktif,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon'      => 'nullable|string|max:255',
            'link'      => 'nullable|string|max:500',
            'warna'     => 'nullable|string|max:255',
            'kategori'  => 'nullable|string|max:255',
            'urutan'    => 'nullable|integer',
            'aktif'     => 'nullable|boolean'
        ]);

        $layanan = Layanan::create($validated);
        return response()->json($layanan, 201);
    }

    public function show(Layanan $layanan)
    {
        return response()->json($layanan);
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'nama'      => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon'      => 'nullable|string|max:255',
            'link'      => 'nullable|string|max:500',
            'warna'     => 'nullable|string|max:255',
            'kategori'  => 'nullable|string|max:255',
            'urutan'    => 'nullable|integer',
            'aktif'     => 'nullable|boolean'
        ]);

        $layanan->update($validated);
        return response()->json($layanan);
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return response()->json(null, 204);
    }
}
