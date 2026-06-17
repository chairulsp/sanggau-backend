# 🔍 Troubleshooting Guide - Sanggau Backend

Panduan untuk mengatasi error yang mungkin terjadi setelah deployment.

---

## 🚨 Error: 500 Internal Server Error

### Gejala
- Website menampilkan "500 Internal Server Error"
- Halaman putih kosong
- "The server encountered an internal error"

### Penyebab Umum
1. Cache Laravel masih menyimpan konfigurasi lama
2. Permission folder storage/bootstrap salah
3. Error di file .env (format salah, typo)
4. PHP extension yang diperlukan belum aktif
5. Middleware yang bermasalah (DecodeBase64Input)

### Solusi

#### Step 1: Clear Cache
```bash
# Via SSH
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Via Browser
Upload clear-cache.php dan akses via browser
```

#### Step 2: Check File Permissions
Via File Manager cPanel, set permission:
```
storage/              → 755 atau 775
storage/logs/         → 755 atau 775
storage/framework/    → 755 atau 775
bootstrap/cache/      → 755 atau 775
```

#### Step 3: Check Error Log
Lokasi log file:
- cPanel → Errors
- `storage/logs/laravel.log`
- PHP error_log di server

Cari baris dengan kata "ERROR" atau "EXCEPTION" untuk tahu penyebab pastinya.

#### Step 4: Verify .env File
- Pastikan tidak ada syntax error
- Pastikan tidak ada spasi di awal/akhir value
- Pastikan menggunakan quotes jika value ada spasi

#### Step 5: Verify Kernel.php
Pastikan `DecodeBase64Input` sudah di-disable:
```php
// \App\Http\Middleware\DecodeBase64Input::class, // DISABLED
```

---

## 🔐 Error: SQLSTATE[HY000] [1045] Access denied for user

### Gejala
```
SQLSTATE[HY000] [1045] Access denied for user 'username'@'localhost' 
(using password: YES)
```

### Penyebab
- Username database salah
- Password database salah
- User belum di-assign ke database
- User tidak punya privileges yang cukup

### Solusi

#### Step 1: Verify Kredensial
Login ke cPanel → MySQL® Databases:
1. Check nama database di section "Current Databases"
2. Check username di section "Current Users"
3. Check user sudah di-add ke database di section "Add User To Database"

#### Step 2: Test Koneksi Manual
Buat file `test-connection.php`:
```php
<?php
$host = 'localhost';
$db   = 'YOUR_DATABASE';
$user = 'YOUR_USERNAME';
$pass = 'YOUR_PASSWORD';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✅ Connected successfully!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
```

Akses file ini via browser untuk test koneksi.
⚠️ **HAPUS file ini setelah test!**

#### Step 3: Update .env
Jika kredensial berbeda, update di file `.env`:
```env
DB_DATABASE=nama_database_yang_benar
DB_USERNAME=username_yang_benar
DB_PASSWORD=password_yang_benar
```

#### Step 4: Clear Cache
Setelah update .env, WAJIB clear cache:
```bash
php artisan config:clear
```

#### Step 5: Check User Privileges
Di cPanel → MySQL® Databases:
1. Scroll ke "Add User To Database"
2. Pastikan user Anda ada di list
3. Klik "Manage Privileges"
4. Pastikan "ALL PRIVILEGES" sudah checked

---

## 🌐 Error: SQLSTATE[HY000] [2002] Connection refused

### Gejala
```
SQLSTATE[HY000] [2002] Connection refused
atau
SQLSTATE[HY000] [2002] No such file or directory
```

### Penyebab
- DB_HOST menggunakan `127.0.0.1` instead of `localhost`
- MySQL service tidak running (jarang di shared hosting)
- Socket path salah

### Solusi

#### Step 1: Ubah DB_HOST
Di file `.env`, ubah:
```env
# SALAH
DB_HOST=127.0.0.1

# BENAR
DB_HOST=localhost
```

#### Step 2: Clear Cache
```bash
php artisan config:clear
```

#### Step 3: Try Alternative Socket
Jika masih error, coba tambahkan unix socket di `config/database.php`:
```php
'mysql' => [
    // ...
    'unix_socket' => '/var/lib/mysql/mysql.sock', // atau path socket di server Anda
],
```

Path socket bisa berbeda per hosting. Tanya hosting support jika perlu.

---

## 🚫 Error: 403 Forbidden saat Save Data

### Gejala
- Saat create/update berita dapat error 403
- "Access Denied" atau "Forbidden"
- Request ditolak oleh server

### Penyebab
- Middleware `DecodeBase64Input` memproses request dengan cara yang salah
- CSRF token tidak valid
- ModSecurity di cPanel memblok request

### Solusi

#### Step 1: Disable DecodeBase64Input
Di file `app/Http/Kernel.php`, pastikan baris ini di-comment:
```php
protected $middleware = [
    // ...
    // \App\Http\Middleware\DecodeBase64Input::class, // DISABLED
];
```

Upload file Kernel.php yang sudah diperbaiki.

#### Step 2: Clear Cache
```bash
php artisan config:clear
php artisan route:clear
```

#### Step 3: Check ModSecurity
Di cPanel:
1. Cari "ModSecurity" atau "Security"
2. Check jika ada rule yang memblok request
3. Whitelist domain Anda jika perlu
4. Atau disable ModSecurity sementara untuk test

#### Step 4: Check .htaccess
Pastikan tidak ada rule di `.htaccess` yang memblok POST request.

---

## 🔑 Error: Login Tidak Berfungsi

### Gejala
- Kredensial benar tapi tidak bisa login
- Redirect terus ke halaman login
- Token tidak tersimpan

### Penyebab
- Session tidak berfungsi
- CORS configuration salah
- Cookie tidak bisa di-set
- Token Sanctum bermasalah

### Solusi

#### Step 1: Check Session Configuration
Di file `.env`:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Pastikan folder `storage/framework/sessions` ada dan writable (permission 755/775).

#### Step 2: Check CORS
Di file `config/cors.php`:
```php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://www.diskominfo.sanggau.go.id',
    'https://api.diskominfo.sanggau.go.id',
],
```

#### Step 3: Clear All Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### Step 4: Check Browser
- Clear browser cache dan cookies
- Coba login di incognito/private mode
- Coba browser lain

#### Step 5: Check Database Token Table
Pastikan tabel `personal_access_tokens` ada di database.

Jika tidak ada, run migration:
```bash
php artisan migrate
```

---

## 📁 Error: Upload File Gagal

### Gejala
- Error saat upload gambar/dokumen
- "File upload failed"
- "The file ... could not be uploaded"

### Penyebab
- Folder storage tidak writable
- PHP upload_max_filesize terlalu kecil
- post_max_size terlalu kecil
- Disk space server penuh

### Solusi

#### Step 1: Check Folder Permission
Set permission untuk folder upload:
```
storage/app/          → 755 atau 775
storage/app/public/   → 755 atau 775
public/uploads/       → 755 atau 775 (jika ada)
```

#### Step 2: Check PHP Configuration
Di cPanel → Select PHP Version → Options:

Pastikan setting ini cukup besar:
```
upload_max_filesize   → 20M atau lebih
post_max_size         → 25M atau lebih
max_execution_time    → 300
memory_limit          → 256M atau lebih
```

#### Step 3: Create Symbolic Link
Jika menggunakan storage link, pastikan symbolic link sudah dibuat:
```bash
php artisan storage:link
```

#### Step 4: Check Disk Space
Via cPanel → Disk Usage, pastikan masih ada space tersedia.

---

## 🔄 Error: Cache Tidak Bisa Di-Clear

### Gejala
- Command `php artisan config:clear` gagal
- "Permission denied" saat clear cache
- Cache masih ada setelah clear

### Solusi

#### Step 1: Manual Delete via File Manager
Hapus file-file ini via cPanel File Manager:
```
bootstrap/cache/config.php
bootstrap/cache/routes-v7.php
bootstrap/cache/services.php
bootstrap/cache/packages.php
```

#### Step 2: Fix Permissions
Set permission folder:
```
bootstrap/cache/   → 755 atau 775
```

#### Step 3: Check Ownership
Pastikan file-file owned by user yang benar (biasanya username cPanel Anda).

Via SSH:
```bash
# Ganti owner seluruh folder
chown -R username:username /path/to/app

# Atau specific folder
chown -R username:username storage
chown -R username:username bootstrap/cache
```

---

## 🔧 Error: composer install Gagal

### Gejala
- `composer install` error
- Dependencies tidak ter-install
- "Your requirements could not be resolved"

### Solusi

#### Step 1: Check PHP Version
```bash
php -v
```

Laravel 8.x membutuhkan PHP 7.3 atau lebih tinggi.

Ubah PHP version di cPanel → MultiPHP Manager.

#### Step 2: Update Composer
```bash
composer self-update
```

#### Step 3: Clear Composer Cache
```bash
composer clear-cache
```

#### Step 4: Install dengan --no-dev
Di production, install tanpa dev dependencies:
```bash
composer install --no-dev --optimize-autoloader
```

#### Step 5: Memory Limit
Jika error "out of memory":
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

---

## 📊 Diagnostic Tool Usage

### Upload Troubleshooting Tool
1. Upload `server-troubleshooting.php` ke root folder
2. Akses: `https://api.diskominfo.sanggau.go.id/server-troubleshooting.php`
3. Check setiap section untuk identifikasi masalah
4. **HAPUS file setelah selesai!**

### Informasi yang Ditampilkan:
- ✅ PHP Version & Extensions
- ✅ File & Folder Permissions
- ✅ Environment Configuration (.env)
- ✅ Database Connection Status
- ✅ Laravel Cache Status
- ✅ Recent Error Logs

---

## 📞 Kapan Harus Hubungi Support?

Hubungi hosting support jika:
- MySQL service tidak running
- Permission error yang tidak bisa diperbaiki
- Server resource limit (CPU, RAM, disk space)
- Network/connectivity issues
- PHP extension tidak bisa di-enable

Hubungi developer jika:
- Logic error di code
- Bug di aplikasi
- Feature tidak berfungsi dengan benar
- Database schema issue

---

## 🔐 Security Checklist After Troubleshooting

Setelah troubleshooting, pastikan:

- [ ] File `test-connection.php` sudah dihapus (jika dibuat)
- [ ] File `clear-cache.php` sudah dihapus
- [ ] File `server-troubleshooting.php` sudah dihapus
- [ ] File temporary lainnya sudah dihapus
- [ ] `APP_DEBUG=false` di .env production
- [ ] Error log tidak accessible public
- [ ] .env tidak ter-commit ke Git

---

## 📝 Logging & Monitoring

### Check Laravel Logs
```bash
# Via SSH
tail -f storage/logs/laravel.log

# Via File Manager
Edit file: storage/logs/laravel.log
```

### Check PHP Error Log
Lokasi berbeda per hosting, biasanya:
- cPanel → Errors
- `/home/username/public_html/error_log`
- `/var/log/php_errors.log`

### Enable Debug Mode Sementara
Hanya untuk troubleshooting, jangan lupa disable setelah selesai:

Di `.env`:
```env
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug
```

⚠️ **DISABLE setelah troubleshooting selesai!**

---

## 🆘 Emergency Rollback

Jika semua solusi gagal dan website harus cepat up:

### Quick Rollback Steps
1. Restore database backup (via phpMyAdmin)
2. Restore file .env ke versi sebelumnya
3. Restore file code yang berubah
4. Clear cache
5. Test website

### Backup Locations
- Database: Download via phpMyAdmin → Export
- Files: Download via File Manager atau FTP
- .env: Keep copy di local machine

---

**Last Updated:** 12 Juni 2026  
**Maintainer:** Sanggau Development Team  
**Support:** [Contact info here]
