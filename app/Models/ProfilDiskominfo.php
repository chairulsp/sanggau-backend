<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDiskominfo extends Model
{
    protected $table = 'profil_diskominfos';
    
    protected $fillable = [
        'nama_dinas', 'singkatan', 'nama_kepala', 'nip_kepala', 'foto_kepala',
        'nama_kabupaten', 'visi', 'misi', 'sejarah', 'tupoksi',
        'alamat', 'telepon', 'fax', 'email', 'website', 'jam_kerja',
        'facebook', 'twitter', 'instagram', 'youtube', 'logo',
    ];
}
