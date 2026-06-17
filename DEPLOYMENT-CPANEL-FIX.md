# 🔧 Panduan Perbaikan Deployment cPanel - Diskominfo Sanggau

## ⚠️ Masalah yang Terjadi

Setelah menggunakan Antigravity untuk memperbaiki error 403, website mengalami:
- ❌ Server Error 500
- ❌ Tidak bisa login
- ❌ Tidak terhubung ke database

## 🔍 Penyebab Masalah

1. **DecodeBase64Input Middleware** ditambahkan ke global middleware, menyebabkan server error
2. **Konfigurasi Database** di file `.env` masih menggunakan kredensial lokal XAMPP
3. **File yang di-upload ke cPanel** belum disesuaikan dengan environment production

## ✅ Langkah Perbaikan

### 1. Upload File yang Sudah Diperbaiki ke cPanel

File yang perlu di-upload ulang:
- `app/Http/Kernel.php` (DecodeBase64Input sudah di-disable)

### 2. Konfigurasi Database di cPanel

**A. Cek Kredensial Database di cPanel:**

1. Login ke **cPanel** Anda
2. Buka **"MySQL® Databases"**
3. Catat informasi berikut:
   - Nama Database (biasanya: `username_namadb`, contoh: `diskomin_sanggau_db`)
   - Username Database (biasanya: `username_dbuser`, contoh: `diskomin_sanggau_user`)
   - Password Database (yang Anda buat saat setup)

**B. Edit File `.env` di Server (via File Manager atau FTP):**

Buka file `.env` di server production dan update bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=diskomin_sanggau_db        # Ganti dengan nama database Anda
DB_USERNAME=diskomin_sanggau_user      # Ganti dengan username database Anda
DB_PASSWORD=password_database_anda      # Ganti dengan password database Anda
```

⚠️ **PENTING:** 
- Gunakan `localhost` untuk `DB_HOST`, BUKAN `127.0.0.1`
- Pastikan tidak ada spasi di awal/akhir
- Nama database di cPanel biasanya ada prefix username (contoh: `diskomin_`)

### 3. Clear Cache di Server

Setelah update `.env`, jalankan perintah ini via **Terminal SSH** atau **cPanel Terminal**:

```bash
cd public_html  # atau folder aplikasi Anda

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache (opsional tapi direkomendasikan)
php artisan config:cache
php artisan route:cache
```

Jika tidak ada akses SSH, buat file PHP temporary di root folder:

**`clear-cache.php`:**
```php
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');

echo "Cache cleared successfully!";
?>
```

Akses: `https://api.diskominfo.sanggau.go.id/clear-cache.php`

⚠️ **Hapus file ini setelah selesai untuk keamanan!**

### 4. Verifikasi Koneksi Database

Buat file test koneksi database:

**`test-db.php`:**
```php
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $db = $app->make('db');
    $pdo = $db->connection()->getPdo();
    echo "✅ Database connected successfully!<br>";
    echo "Database name: " . $db->connection()->getDatabaseName();
} catch (\Exception $e) {
    echo "❌ Database connection failed!<br>";
    echo "Error: " . $e->getMessage();
}
?>
```

Akses: `https://api.diskominfo.sanggau.go.id/test-db.php`

⚠️ **Hapus file ini setelah selesai untuk keamanan!**

### 5. Set Permission yang Benar

Pastikan folder ini memiliki permission yang tepat (via File Manager cPanel):

```
storage/          → 755 atau 775
storage/logs/     → 755 atau 775
bootstrap/cache/  → 755 atau 775
```

Untuk file:
```
.env             → 644
```

### 6. Check PHP Version

Pastikan PHP version di cPanel sesuai dengan requirement Laravel:
- **PHP 7.4** atau lebih tinggi (disarankan PHP 8.0+)

Cara ubah PHP version di cPanel:
1. Cari **"MultiPHP Manager"** atau **"Select PHP Version"**
2. Pilih domain Anda
3. Set ke **PHP 8.0** atau **PHP 8.1**

### 7. Enable Required PHP Extensions

Pastikan extension ini enabled di cPanel (via **Select PHP Version** → **Extensions**):

- ✅ `pdo_mysql`
- ✅ `mbstring`
- ✅ `xml`
- ✅ `curl`
- ✅ `openssl`
- ✅ `tokenizer`
- ✅ `json`
- ✅ `fileinfo`

## 🧪 Testing Setelah Perbaikan

### Test Login API:

```bash
curl -X POST https://api.diskominfo.sanggau.go.id/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"your_password"}'
```

### Test di Browser:
1. Buka frontend: `https://diskominfo.sanggau.go.id`
2. Coba login ke admin panel
3. Coba buat berita baru

## 🚨 Troubleshooting

### Error: "SQLSTATE[HY000] [1045] Access denied"
- ❌ **Penyebab:** Username/password database salah
- ✅ **Solusi:** Double-check kredensial di cPanel → MySQL Databases

### Error: "SQLSTATE[HY000] [2002] Connection refused"
- ❌ **Penyebab:** DB_HOST salah atau database tidak running
- ✅ **Solusi:** Gunakan `localhost` bukan `127.0.0.1`

### Error: "500 Internal Server Error"
- ❌ **Penyebab:** File permission salah atau cache error
- ✅ **Solusi:** 
  1. Clear cache (lihat langkah 3)
  2. Set permission yang benar (lihat langkah 5)
  3. Check error di `storage/logs/laravel.log`

### Login Tidak Berfungsi
- ❌ **Penyebab:** Session/token issue
- ✅ **Solusi:**
  1. Pastikan `APP_KEY` sudah di-set
  2. Clear cache browser
  3. Pastikan CORS sudah benar di `config/cors.php`

## 📝 Catatan Penting

1. **Jangan pernah commit file `.env`** ke Git (sudah ada di `.gitignore`)
2. **Backup database** sebelum melakukan perubahan besar
3. **Simpan kredensial database** di tempat aman (password manager)
4. **Set `APP_DEBUG=false`** di production untuk keamanan
5. **Monitor log** di `storage/logs/laravel.log` untuk error

## 📞 Kontak Support

Jika masalah masih berlanjut:
1. Check `storage/logs/laravel.log` untuk error detail
2. Contact hosting support untuk bantuan database/server
3. Hubungi developer untuk troubleshooting lebih lanjut

---

**Last Updated:** 12 Juni 2026
**Status:** ✅ Fixed - DecodeBase64Input middleware disabled
