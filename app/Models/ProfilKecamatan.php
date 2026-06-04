<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilKecamatan extends Model
{
    use HasFactory;
    protected $table = 'profil_kecamatans';
    protected $fillable = [
        'nama_kecamatan', 'nama_kabupaten', 'nama_camat', 'nip_camat',
        'visi', 'misi', 'sejarah', 'alamat', 'telepon', 'email',
        'website', 'logo', 'foto_camat', 'jumlah_desa',
        'jumlah_penduduk', 'luas_wilayah'
    ];
}
