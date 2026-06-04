<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriVideo extends Model
{
    protected $table = 'galeri_videos';

    protected $fillable = [
        'judul', 'url_youtube', 'thumbnail', 'deskripsi', 'tanggal', 'aktif', 'urutan',
    ];

    protected $casts = ['aktif' => 'boolean'];
}
