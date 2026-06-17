<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = ['judul', 'subjudul', 'gambar', 'link', 'aktif', 'urutan', 'posisi'];
    protected $casts = ['aktif' => 'boolean'];
    
    /**
     * Accessor untuk gambar - otomatis convert ke full URL saat diambil dari DB
     * Hanya untuk API response, tidak mengubah data di database
     */
    public function getGambarAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Jika sudah full URL, return as-is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        // Convert ke full URL untuk API response
        $path = ltrim($value, '/');
        
        // Get APP_URL dari .env, jika tidak ada gunakan request URL
        $appUrl = config('app.url');
        if (!$appUrl || $appUrl === 'http://localhost:8000') {
            // Fallback: gunakan domain dari request
            $appUrl = request()->getSchemeAndHttpHost();
        }
        
        $appUrl = rtrim($appUrl, '/');
        return $appUrl . '/' . $path;
    }
}
