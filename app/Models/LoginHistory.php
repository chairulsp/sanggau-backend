<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id', 'email', 'ip_address',
        'browser', 'device', 'os', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parse User Agent string to extract Browser, OS, and Device.
     *
     * @param string|null $ua
     * @return array
     */
    public static function parseUserAgent($ua)
    {
        $browser = 'Unknown Browser';
        $os = 'Unknown OS';
        $device = 'Desktop';

        if (empty($ua)) {
            return compact('browser', 'os', 'device');
        }

        // 1. Browser Detection
        if (preg_match('/MSIE/i', $ua) && !preg_match('/Opera/i', $ua)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/Netscape/i', $ua)) {
            $browser = 'Netscape';
        }

        // 2. OS Detection
        if (preg_match('/windows|win32/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'Mac OS';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/android/i', $ua)) {
            $os = 'Android';
        }

        // 3. Device Detection
        if (preg_match('/mobile|phone|iphone|ipod|android|silk|kindle|opera mobi|opera mini/i', $ua)) {
            $device = 'Mobile';
        } elseif (preg_match('/ipad|tablet|playbook|kindle/i', $ua)) {
            $device = 'Tablet';
        }

        return compact('browser', 'os', 'device');
    }
}
