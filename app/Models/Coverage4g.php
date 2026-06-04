<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coverage4g extends Model
{
    protected $table = 'coverage4g';

    protected $fillable = [
        'kecamatan',
        'ibu_kota',
        'persen',
        'urutan',
    ];

    protected $casts = [
        'persen'  => 'integer',
        'urutan'  => 'integer',
    ];
}
