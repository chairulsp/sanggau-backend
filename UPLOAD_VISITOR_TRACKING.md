# Instruksi Upload: Visitor Tracking Backend

## File yang Perlu Diupload ke Server Domainesia

### 1. Migration File
**Lokasi Lokal:** `database/migrations/2026_05_23_000001_create_visitors_table.php`
**Lokasi Server:** `/home/diskomi5/laravel/database/migrations/2026_05_23_000001_create_visitors_table.php`

### 2. Model File
**Lokasi Lokal:** `app/Models/Visitor.php`
**Lokasi Server:** `/home/diskomi5/laravel/app/Models/Visitor.php`

### 3. Controller File
**Lokasi Lokal:** `app/Http/Controllers/Api/VisitorController.php`
**Lokasi Server:** `/home/diskomi5/laravel/app/Http/Controllers/Api/VisitorController.php`

### 4. Routes File (Update)
**Lokasi Lokal:** `routes/api.php`
**Lokasi Server:** `/home/diskomi5/laravel/routes/api.php`

---

## Langkah-langkah Upload via File Manager Domainesia

### Step 1: Upload Migration
1. Login ke cPanel Domainesia
2. Buka File Manager
3. Navigate ke `/home/diskomi5/laravel/database/migrations/`
4. Upload file `2026_05_23_000001_create_visitors_table.php`

### Step 2: Upload Model
1. Navigate ke `/home/diskomi5/laravel/app/Models/`
2. Upload file `Visitor.php`

### Step 3: Upload Controller
1. Navigate ke `/home/diskomi5/laravel/app/Http/Controllers/Api/`
2. Upload file `VisitorController.php`

### Step 4: Update Routes
1. Navigate ke `/home/diskomi5/laravel/routes/`
2. **BACKUP** file `api.php` yang ada (rename ke `api.php.backup`)
3. Upload file `api.php` yang baru

---

## Langkah-langkah Setelah Upload

### 1. Jalankan Migration
Buat file `migrate.php` di `/home/diskomi5/public_html/`:

```php
<?php
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('migrate', ['--force' => true]);
echo "Migration status: " . $status . "\n";
echo "Migration completed!\n";
```

Akses: `https://diskominfo.sanggau.go.id/migrate.php`

### 2. Clear Cache
Akses file yang sudah ada: `https://diskominfo.sanggau.go.id/clearcache.php`

Atau buat baru jika belum ada:
```php
<?php
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('config:clear');
$kernel->call('route:clear');
$kernel->call('cache:clear');
$kernel->call('view:clear');

echo "All caches cleared successfully!";
```

### 3. Verifikasi Database
Login ke phpMyAdmin dan cek apakah tabel `visitors` sudah dibuat dengan kolom:
- id
- session_id
- ip_address
- halaman
- referrer
- user_agent
- device
- browser
- os
- is_new
- created_at
- updated_at

### 4. Test API Endpoint
**Test Track Endpoint:**
```bash
curl -X POST https://diskominfo.sanggau.go.id/api/track \
  -H "Content-Type: application/json" \
  -d '{
    "session_id": "test-session-123",
    "halaman": "/",
    "referrer": ""
  }'
```

Expected response:
```json
{"ok": true, "is_new": true}
```

**Test Stats Endpoint (Admin):**
```
https://diskominfo.sanggau.go.id/api/admin/visitor-stats
```
(Perlu login sebagai superadmin)

---

## Frontend Sudah Siap

Frontend sudah di-deploy ke Vercel dengan:
- `VisitorTracker` component di public layout
- Dashboard admin sudah fetch visitor stats
- Auto-tracking setiap page view

Setelah backend diupload dan migration dijalankan, tracking akan langsung aktif!

---

## Troubleshooting

### Error: Table doesn't exist
- Pastikan migration sudah dijalankan
- Cek di phpMyAdmin apakah tabel `visitors` ada

### Error: Route not found
- Clear route cache: akses `clearcache.php`
- Pastikan file `api.php` sudah terupload dengan benar

### Error: Class not found
- Clear config cache
- Pastikan file `Visitor.php` dan `VisitorController.php` sudah terupload

### Stats tidak muncul di dashboard
- Cek apakah user login sebagai superadmin
- Cek console browser untuk error
- Cek network tab untuk response API

---

## Keamanan

Route `/api/track` adalah **public** (tidak perlu auth) karena digunakan untuk tracking pengunjung.

Route `/api/admin/visitor-stats` adalah **protected** (hanya superadmin) untuk melihat statistik.

---

## Selesai!

Setelah semua langkah di atas, visitor tracking akan aktif dan Anda bisa melihat statistik pengunjung real-time di dashboard admin.
