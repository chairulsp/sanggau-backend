<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumens';

    protected $fillable = [
        'judul', 'kategori', 'file', 'file_url', 'deskripsi', 'tahun', 'downloads', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];
}
