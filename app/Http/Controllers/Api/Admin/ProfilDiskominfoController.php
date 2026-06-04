<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfilDiskominfo;

class ProfilDiskominfoController extends Controller
{
    public function show()
    {
        $profil = ProfilDiskominfo::first();
        return response()->json($profil);
    }

    public function update(Request $request)
    {
        $profil = ProfilDiskominfo::first();
        if (!$profil) $profil = new ProfilDiskominfo();

        $profil->fill($request->only([
            'nama_dinas', 'singkatan', 'nama_kepala', 'nip_kepala', 'foto_kepala',
            'nama_kabupaten', 'visi', 'misi', 'sejarah', 'tupoksi',
            'alamat', 'telepon', 'fax', 'email', 'website', 'jam_kerja',
            'facebook', 'twitter', 'instagram', 'youtube', 'logo',
        ]));
        $profil->save();

        return response()->json(['message' => 'Profil Diskominfo berhasil diperbarui.', 'data' => $profil]);
    }
}
