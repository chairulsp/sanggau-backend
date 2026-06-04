<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduans';

    protected $fillable = [
        'nama', 'email', 'telepon', 'subjek', 'pesan', 'status', 'balasan', 'dibalas_at',
    ];

    protected $casts = ['dibalas_at' => 'datetime'];
}
