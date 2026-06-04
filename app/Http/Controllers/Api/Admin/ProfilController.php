<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilKecamatan;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function show()
    {
        $profil = ProfilKecamatan::first();
        return response()->json($profil);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'nama_kabupaten' => 'nullable|string|max:255',
            'nama_camat' => 'nullable|string|max:255',
            'nip_camat' => 'nullable|string|max:100',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|string',
            'foto_camat' => 'nullable|string',
            'jumlah_desa' => 'nullable|string|max:50',
            'jumlah_penduduk' => 'nullable|string|max:50',
            'luas_wilayah' => 'nullable|string|max:50',
        ]);

        $profil = ProfilKecamatan::first();
        if ($profil) {
            $profil->update($validated);
        } else {
            $profil = ProfilKecamatan::create($validated);
        }
        return response()->json($profil);
    }
}
