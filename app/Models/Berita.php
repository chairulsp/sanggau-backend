<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul', 'slug', 'ringkasan', 'konten', 'gambar',
        'penulis', 'kategori', 'tags', 'featured', 'aktif', 'views', 'published_at'
    ];
    protected $casts = [
        'featured' => 'boolean',
        'aktif' => 'boolean',
        'published_at' => 'datetime',
    ];
}
