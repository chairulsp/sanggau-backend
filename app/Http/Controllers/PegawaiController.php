<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    private function fotoUrl(?string $foto): ?string
    {
        if (!$foto) return null;
        if (str_starts_with($foto, 'http')) return $foto;
        return url($foto);
    }

    private function withFoto($items)
    {
        return collect($items)->map(function ($item) {
            $arr = is_array($item) ? $item : $item->toArray();
            $arr['foto'] = $this->fotoUrl($arr['foto'] ?? null);
            return $arr;
        })->values();
    }

    public function index(Request $request)
    {
        $query = Pegawai::where('aktif', true)->orderBy('urutan');
        if ($request->has('bidang') && $request->bidang !== 'semua')
            $query->where('bidang', $request->bidang);
        if ($request->has('status') && $request->status !== 'semua')
            $query->where('status_pegawai', $request->status);
        return response()->json(['success' => true, 'data' => $this->withFoto($query->get())]);
    }

    public function profilPimpinan()
    {
        $kepala = Pegawai::where('jabatan', 'like', '%kepala dinas%')->where('aktif', true)->first();
        if (!$kepala) return response()->json(['success' => true, 'data' => null]);
        return response()->json([
            'success' => true,
            'data' => [
                'id'              => $kepala->id,
                'nama'            => $kepala->nama_lengkap,
                'jabatan'         => $kepala->jabatan,
                'foto'            => $this->fotoUrl($kepala->foto),
                'nip'             => $kepala->nip,
                'sambutan'        => 'Selamat datang di website resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau.',
                'pendidikan'      => $kepala->pendidikan_terakhir ? [$kepala->pendidikan_terakhir] : [],
                'pangkat'         => $kepala->pangkat_golongan,
                'tahun_bergabung' => $kepala->tahun_bergabung,
            ]
        ]);
    }

    public function adminIndex()
    {
        return response()->json(['success' => true, 'data' => $this->withFoto(Pegawai::orderBy('urutan')->get())]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap'        => 'required|string|max:255',
            'nip'                 => 'required|string|max:50|unique:pegawai,nip',
            'jabatan'             => 'required|string|max:100',
            'tipe_jabatan'        => 'required|in:pimpinan,fungsional,pelaksana',
            'bidang'              => 'nullable|string|max:100',
            'status_pegawai'      => 'required|in:PNS,PPPK,Honorer',
            'pangkat_golongan'    => 'nullable|string|max:50',
            'tahun_bergabung'     => 'nullable|integer',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'foto'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan'              => 'nullable|integer',
            'aktif'               => 'nullable',
        ]);

        if ($validator->fails())
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);

        $data = $request->except('foto');
        $data['aktif'] = in_array($request->aktif, ['1', 1, true, 'true'], true);
        if ($data['tipe_jabatan'] === 'pimpinan') $data['bidang'] = null;

        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $dir      = '/home/diskominfo/public_html/uploads/pegawai';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $file->move($dir, $filename);
            $data['foto'] = '/uploads/pegawai/' . $filename;
        }

        $pegawai = Pegawai::create($data);
        $arr = $pegawai->toArray();
        $arr['foto'] = $this->fotoUrl($pegawai->foto);
        return response()->json(['success' => true, 'message' => 'Pegawai berhasil ditambahkan', 'data' => $arr], 201);
    }

    public function show($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan'], 404);
        $arr = $pegawai->toArray();
        $arr['foto'] = $this->fotoUrl($pegawai->foto);
        return response()->json(['success' => true, 'data' => $arr]);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'nama_lengkap'        => 'required|string|max:255',
            'nip'                 => 'required|string|max:50|unique:pegawai,nip,' . $id,
            'jabatan'             => 'required|string|max:100',
            'tipe_jabatan'        => 'required|in:pimpinan,fungsional,pelaksana',
            'bidang'              => 'nullable|string|max:100',
            'status_pegawai'      => 'required|in:PNS,PPPK,Honorer',
            'pangkat_golongan'    => 'nullable|string|max:50',
            'tahun_bergabung'     => 'nullable|integer',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'foto'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan'              => 'nullable|integer',
            'aktif'               => 'nullable',
        ]);

        if ($validator->fails())
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);

        $data = $request->except('foto', 'foto_lama', '_method');
        $data['aktif'] = in_array($request->aktif, ['1', 1, true, 'true'], true);
        if ($data['tipe_jabatan'] === 'pimpinan') $data['bidang'] = null;

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($pegawai->foto && str_contains($pegawai->foto, '/uploads/pegawai/')) {
                $old = '/home/diskominfo/public_html' . $pegawai->foto;
                if (file_exists($old)) unlink($old);
            }
            $file     = $request->file('foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $dir      = '/home/diskominfo/public_html/uploads/pegawai';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $file->move($dir, $filename);
            $data['foto'] = '/uploads/pegawai/' . $filename;
        }

        $pegawai->update($data);
        $updated = $pegawai->refresh();
        $arr = $updated->toArray();
        $arr['foto'] = $this->fotoUrl($updated->foto);
        return response()->json(['success' => true, 'message' => 'Pegawai berhasil diperbarui', 'data' => $arr]);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan'], 404);

        if ($pegawai->foto && str_contains($pegawai->foto, '/uploads/pegawai/')) {
            $path = '/home/diskominfo/public_html' . $pegawai->foto;
            if (file_exists($path)) unlink($path);
        }

        $pegawai->delete();
        return response()->json(['success' => true, 'message' => 'Pegawai berhasil dihapus']);
    }
}
