<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppid extends Model
{
    protected $table = 'ppids';

    protected $fillable = [
        'judul', 'kategori', 'file', 'file_url', 'deskripsi', 'tahun', 'urutan', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];
}
