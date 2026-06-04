<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul', 'deskripsi', 'lokasi', 'gambar',
        'tanggal_mulai', 'tanggal_selesai', 'penyelenggara', 'aktif'
    ];
    protected $casts = [
        'aktif' => 'boolean',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];
}
