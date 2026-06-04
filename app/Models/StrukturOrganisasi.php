<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasis';

    protected $fillable = [
        'nama', 'jabatan', 'nip', 'foto', 'email', 'telepon', 'urutan', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];
}
