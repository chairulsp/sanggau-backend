<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'deskripsi', 'ikon', 'link', 'warna',
        'kategori', 'urutan', 'aktif'
    ];
    protected $casts = [
        'aktif' => 'boolean',
    ];
}
