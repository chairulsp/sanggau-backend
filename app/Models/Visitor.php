<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'session_id', 'ip_address', 'halaman',
        'referrer', 'user_agent', 'device',
        'browser', 'os', 'is_new',
    ];

    protected $casts = [
        'is_new' => 'boolean',
    ];
}
