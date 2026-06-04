<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul', 'konten', 'file', 'tanggal_mulai', 'tanggal_selesai',
        'penting', 'aktif'
    ];
    protected $casts = [
        'penting' => 'boolean',
        'aktif' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
