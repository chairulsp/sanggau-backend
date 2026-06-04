<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laman extends Model
{
    use HasFactory;

    protected $table = 'lamans';

    protected $fillable = [
        'judul', 'slug', 'konten', 'gambar', 'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];
}
