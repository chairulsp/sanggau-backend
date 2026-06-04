<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
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
            'data' => $query->get()
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
                'id' => $kepala->id,
                'nama' => $kepala->nama_lengkap,
                'jabatan' => $kepala->jabatan,
                'foto' => $kepala->foto,
                'nip' => $kepala->nip,
                'sambutan' => 'Selamat datang di website resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau.',
                'pendidikan' => $kepala->pendidikan_terakhir ? [$kepala->pendidikan_terakhir] : [],
                'pangkat' => $kepala->pangkat_golongan,
                'tahun_bergabung' => $kepala->tahun_bergabung,
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
            'data' => $pegawai
        ]);
    }

    // ============================================================
    // ADMIN: POST /api/admin/pegawai
    // ============================================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pegawai,nip',
            'jabatan' => 'required|string|max:100',
            'tipe_jabatan' => 'required|in:pimpinan,fungsional,pelaksana',
            'bidang' => 'nullable|string|max:100',
            'status_pegawai' => 'required|in:PNS,PPPK,Honorer',
            'pangkat_golongan' => 'nullable|string|max:50',
            'tahun_bergabung' => 'nullable|integer',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('foto');
        $data['aktif'] = in_array($request->aktif, ['1', 1, true, 'true'], true);

        if ($data['tipe_jabatan'] === 'pimpinan') {
            $data['bidang'] = null;
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('pegawai', $filename, 'public');
            $data['foto'] = '/storage/' . $path;
        }

        $pegawai = Pegawai::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => $pegawai
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

        return response()->json([
            'success' => true,
            'data' => $pegawai
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
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pegawai,nip,' . $id,
            'jabatan' => 'required|string|max:100',
            'tipe_jabatan' => 'required|in:pimpinan,fungsional,pelaksana',
            'bidang' => 'nullable|string|max:100',
            'status_pegawai' => 'required|in:PNS,PPPK,Honorer',
            'pangkat_golongan' => 'nullable|string|max:50',
            'tahun_bergabung' => 'nullable|integer',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('foto', 'foto_lama', '_method');
        $data['aktif'] = in_array($request->aktif, ['1', 1, true, 'true'], true);

        if ($data['tipe_jabatan'] === 'pimpinan') {
            $data['bidang'] = null;
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($pegawai->foto) {
                $oldPath = str_replace('/storage/', '', $pegawai->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('pegawai', $filename, 'public');
            $data['foto'] = '/storage/' . $path;
        }

        $pegawai->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil diperbarui',
            'data' => $pegawai->refresh()
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

        // Hapus foto
        if ($pegawai->foto) {
            $path = str_replace('/storage/', '', $pegawai->foto);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $pegawai->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil dihapus'
        ]);
    }
}