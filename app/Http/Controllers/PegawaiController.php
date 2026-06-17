<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    // Helper: konversi path foto ke full URL
    private function resolvePhotoUrl(?string $foto): ?string
    {
        if (!$foto) return null;
        if (str_starts_with($foto, 'http')) return $foto;
        return url($foto);
    }

    // Helper: tambahkan foto full URL ke collection
    private function withPhotoUrl($items)
    {
        return collect($items)->map(function ($item) {
            $arr = is_array($item) ? $item : $item->toArray();
            $arr['foto'] = $this->resolvePhotoUrl($arr['foto'] ?? null);
            return $arr;
        })->values();
    }

    // ============================================================
    // PUBLIC: GET /api/pegawai
    // ============================================================
    public function index(Request $request)
    {
        $query = Pegawai::where('aktif', true)->orderBy('urutan');

        if ($request->has('bidang') && $request->bidang !== 'semua') {
            $query->where('bidang', $request->bidang);
        }
        if ($request->has('status') && $request->status !== 'semua') {
            $query->where('status_pegawai', $request->status);
        }

        return response()->json([
            'success' => true,
            'data' => $this->withPhotoUrl($query->get())
        ]);
    }

    // ============================================================
    // PUBLIC: GET /api/profil-pimpinan
    // ============================================================
    public function profilPimpinan()
    {
        $kepala = Pegawai::where('jabatan', 'like', '%kepala dinas%')
            ->where('aktif', true)
            ->first();

        if (!$kepala) {
            return response()->json([
                'success' => true,
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'             => $kepala->id,
                'nama'           => $kepala->nama_lengkap,
                'jabatan'        => $kepala->jabatan,
                'foto'           => $this->resolvePhotoUrl($kepala->foto),
                'nip'            => $kepala->nip,
                'sambutan'       => 'Selamat datang di website resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau.',
                'pendidikan'     => $kepala->pendidikan_terakhir ? [$kepala->pendidikan_terakhir] : [],
                'pangkat'        => $kepala->pangkat_golongan,
                'tahun_bergabung'=> $kepala->tahun_bergabung,
            ]
        ]);
    }

    // ============================================================
    // ADMIN: GET /api/admin/pegawai
    // ============================================================
    public function adminIndex()
    {
        $pegawai = Pegawai::orderBy('urutan')->get();

        return response()->json([
            'success' => true,
            'data' => $this->withPhotoUrl($pegawai)
        ]);
    }

    // ============================================================
    // ADMIN: POST /api/admin/pegawai
    // ============================================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap'       => 'required|string|max:255',
            'nip'                => 'required|string|max:50|unique:pegawai,nip',
            'jabatan'            => 'required|string|max:100',
            'tipe_jabatan'       => 'required|in:pimpinan,fungsional,pelaksana',
            'bidang'             => 'nullable|string|max:100',
            'status_pegawai'     => 'required|in:PNS,PPPK,Honorer',
            'pangkat_golongan'   => 'nullable|string|max:50',
            'tahun_bergabung'    => 'nullable|integer',
            'pendidikan_terakhir'=> 'nullable|string|max:100',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan'             => 'nullable|integer',
            'aktif'              => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->except('foto');
        $data['aktif'] = in_array($request->aktif, ['1', 1, true, 'true'], true);

        if ($data['tipe_jabatan'] === 'pimpinan') {
            $data['bidang'] = null;
        }

        // Upload foto langsung ke public_html/uploads/pegawai/
        if ($request->hasFile('foto')) {
            $file      = $request->file('foto');
            $filename  = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $uploadDir = '/home/diskominfo/public_html/uploads/pegawai';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $data['foto'] = '/uploads/pegawai/' . $filename;
        }

        $pegawai      = Pegawai::create($data);
        $arr          = $pegawai->toArray();
        $arr['foto']  = $this->resolvePhotoUrl($pegawai->foto);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data'    => $arr
        ], 201);
    }

    // ============================================================
    // ADMIN: GET /api/admin/pegawai/{id}
    // ============================================================
    public function show($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan'
            ], 404);
        }

        $arr         = $pegawai->toArray();
        $arr['foto'] = $this->resolvePhotoUrl($pegawai->foto);

        return response()->json([
            'success' => true,
            'data'    => $arr
        ]);
    }

    // ============================================================
    // ADMIN: PUT /api/admin/pegawai/{id}
    // ============================================================
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap'       => 'required|string|max:255',
            'nip'                => 'required|string|max:50|unique:pegawai,nip,' . $id,
            'jabatan'            => 'required|string|max:100',
            'tipe_jabatan'       => 'required|in:pimpinan,fungsional,pelaksana',
            'bidang'             => 'nullable|string|max:100',
            'status_pegawai'     => 'required|in:PNS,PPPK,Honorer',
            'pangkat_golongan'   => 'nullable|string|max:50',
            'tahun_bergabung'    => 'nullable|integer',
            'pendidikan_terakhir'=> 'nullable|string|max:100',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan'             => 'nullable|integer',
            'aktif'              => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->except('foto', 'foto_lama', '_method');
        $data['aktif'] = in_array($request->aktif, ['1', 1, true, 'true'], true);

        if ($data['tipe_jabatan'] === 'pimpinan') {
            $data['bidang'] = null;
        }

        // Upload foto baru langsung ke public_html/uploads/pegawai/
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika di uploads
            if ($pegawai->foto && str_contains($pegawai->foto, '/uploads/')) {
                $oldPath = '/home/diskominfo/public_html' . $pegawai->foto;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file      = $request->file('foto');
            $filename  = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $uploadDir = '/home/diskominfo/public_html/uploads/pegawai';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $data['foto'] = '/uploads/pegawai/' . $filename;
        }

        $pegawai->update($data);
        $updated     = $pegawai->refresh();
        $arr         = $updated->toArray();
        $arr['foto'] = $this->resolvePhotoUrl($updated->foto);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil diperbarui',
            'data'    => $arr
        ]);
    }

    // ============================================================
    // ADMIN: DELETE /api/admin/pegawai/{id}
    // ============================================================
    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan'
            ], 404);
        }

        // Hapus foto dari public_html/uploads/pegawai/
        if ($pegawai->foto && !str_starts_with($pegawai->foto, 'http')) {
            $fotoPath = '/home/diskominfo/public_html' . $pegawai->foto;
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        $pegawai->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil dihapus'
        ]);
    }
}
