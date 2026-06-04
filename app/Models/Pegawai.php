<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'jabatan',
        'tipe_jabatan',
        'bidang',
        'status_pegawai',
        'pangkat_golongan',
        'tahun_bergabung',
        'pendidikan_terakhir',
        'foto',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'tahun_bergabung' => 'integer',
        'urutan' => 'integer',
    ];
}