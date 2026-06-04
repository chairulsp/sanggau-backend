<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistik extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'nilai', 'satuan', 'ikon', 'warna',
        'urutan', 'aktif'
    ];
    protected $casts = [
        'aktif' => 'boolean',
    ];
}
