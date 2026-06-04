<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['label', 'url', 'ikon', 'urutan', 'aktif', 'buka_tab_baru'];
    protected $casts = ['aktif' => 'boolean', 'buka_tab_baru' => 'boolean'];
}
