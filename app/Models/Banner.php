<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = ['judul', 'subjudul', 'gambar', 'link', 'aktif', 'urutan'];
    protected $casts = ['aktif' => 'boolean'];
}
