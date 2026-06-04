<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'singkatan', 'deskripsi', 'kepala', 'alamat',
        'telepon', 'email', 'website', 'logo', 'kategori', 'aktif'
    ];
    protected $casts = [
        'aktif' => 'boolean',
    ];
}
