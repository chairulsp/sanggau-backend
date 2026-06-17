# 🚨 QUICK FIX - Production Error (Server 500 & Database)

## ⚡ Langkah Cepat (5 Menit)

### 1️⃣ Upload File yang Diperbaiki

Upload file ini ke cPanel (via File Manager atau FTP):
```
app/Http/Kernel.php  ← DecodeBase64Input sudah di-disable
```

### 2️⃣ Edit File .env di Server

Via **cPanel File Manager**, edit file `.env` di root folder:

```env
# Ganti bagian DATABASE dengan kredensial cPanel Anda:

DB_CONNECTION=mysql
DB_HOST=localhost                    # Gunakan localhost, BUKAN 127.0.0.1
DB_PORT=3306
DB_DATABASE=diskomin_sanggau_db     # Nama database di cPanel (ada prefix)
DB_USERNAME=diskomin_sanggau_user   # Username database di cPanel
DB_PASSWORD=password_anda_disini    # Password database di cPanel
```

💡 **Cara dapat kredensial database:**
- Login cPanel → **MySQL® Databases**
- Lihat nama database & username (biasanya ada prefix username di depan)
- Password adalah yang Anda buat saat setup database

### 3️⃣ Clear Cache

**OPSI A - Via Browser (Mudah):**
1. Upload file `clear-cache.php` ke root folder
2. Akses: `https://api.diskominfo.sanggau.go.id/clear-cache.php`
3. Klik tombol **"Clear All Cache"**
4. Klik tombol **"Rebuild Cache"**
5. **HAPUS file `clear-cache.php`** setelah selesai!

**OPSI B - Via Terminal SSH:**
```bash
cd public_html  # atau folder aplikasi
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

**OPSI C - Manual (File Manager):**
Hapus file-file ini via File Manager:
```
bootstrap/cache/config.php
bootstrap/cache/routes-v7.php
bootstrap/cache/services.php
```

### 4️⃣ Test

Coba akses website:
- Frontend: `https://diskominfo.sanggau.go.id`
- Login admin
- Coba buat berita

## 🔍 Troubleshooting

### Masih Error?

Upload `server-troubleshooting.php` dan akses:
```
https://api.diskominfo.sanggau.go.id/server-troubleshooting.php
```

File ini akan show:
- ✅ PHP version & extensions
- ✅ File permissions
- ✅ Database connection status
- ✅ Recent error logs

⚠️ **HAPUS file ini setelah selesai!**

### Error "Access Denied" Database?

Cek di cPanel → MySQL Databases:
1. Pastikan database ada
2. Pastikan user sudah di-assign ke database
3. Pastikan user punya **ALL PRIVILEGES**

### Masih 500 Error?

Check log error:
- Via cPanel: **Errors** atau **Error Log**
- Atau buka: `storage/logs/laravel.log`

## ✅ Checklist

- [ ] File `Kernel.php` sudah di-upload
- [ ] File `.env` sudah diupdate dengan kredensial database cPanel
- [ ] Cache sudah di-clear
- [ ] Database connection berhasil (test via troubleshooting tool)
- [ ] Website bisa diakses
- [ ] Login admin berfungsi
- [ ] Bisa buat berita
- [ ] File troubleshooting sudah dihapus

## 📋 Detail Lengkap

Lihat file: **DEPLOYMENT-CPANEL-FIX.md**

---
**Created:** 12 Juni 2026
**Status:** Ready to deploy
