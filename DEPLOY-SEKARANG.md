# 🚀 DEPLOY SEKARANG - Sanggau Backend

**URGENT FIX - Kredensial Database Sudah Benar**

---

## ✅ Yang Sudah Diperbaiki di Local

1. ✅ File `.env` - `DB_HOST` sudah diubah dari `127.0.0.1` ke `localhost`
2. ✅ File `.env` - Kredensial database production sudah benar:
   - Database: `diskominfo_sanggaudb`
   - Username: `diskominfo_sanggau`
   - Password: `diskominfo_sanggau26`
3. ✅ File `app/Http/Kernel.php` - DecodeBase64Input middleware sudah di-disable
4. ✅ File `.env.production` - Backup dengan kredensial yang benar

---

## 🎯 LANGKAH DEPLOYMENT (4 FILE!)

### ⚠️ STEP 0: Fix Storage Structure (CRITICAL!)

**UPLOAD FILE INI DULU:**
- **`fix-storage-structure.php`** (auto-create missing folders)

**Akses via browser:**
```
https://api.diskominfo.sanggau.go.id/fix-storage-structure.php
```

Script akan membuat folder `storage/framework` yang hilang.

**HAPUS file setelah selesai!**

---

### STEP 1: Upload File ke cPanel (2 menit)

Login ke **cPanel** → **File Manager** → Masuk ke folder aplikasi

Upload **2 FILE INI** (overwrite yang lama):

1. **`app/Http/Kernel.php`** 
   - DecodeBase64Input sudah di-disable
   - Fix error 500

2. **`.env`**
   - DB_HOST = localhost ✅
   - DB_DATABASE = diskominfo_sanggaudb ✅
   - DB_USERNAME = diskominfo_sanggau ✅
   - DB_PASSWORD = diskominfo_sanggau26 ✅

3. **`clear-cache.php`** (temporary, akan dihapus nanti)
   - Tool untuk clear cache via browser

---

### STEP 2: Clear Cache (1 menit)

Akses via browser:
```
https://api.diskominfo.sanggau.go.id/clear-cache.php
```

Klik tombol:
1. **"Clear All Cache"** → Tunggu hingga ✅
2. **"Rebuild Cache"** → Tunggu hingga ✅

---

### STEP 3: Hapus File clear-cache.php

Via **File Manager cPanel**, hapus file:
```
clear-cache.php
```

⚠️ **PENTING:** File ini expose sistem info, wajib dihapus!

---

### STEP 4: Test Website

1. **Frontend**: `https://diskominfo.sanggau.go.id`
   - ✅ Homepage loading

2. **Admin Login**: Login ke admin panel
   - ✅ Login berhasil

3. **Test Buat Berita**:
   - ✅ Form bisa dibuka
   - ✅ Upload gambar berhasil
   - ✅ Simpan berhasil (TIDAK ADA error 403!)

---

## 🔍 Jika Masih Ada Error

### Upload Troubleshooting Tool

1. Upload file: `server-troubleshooting.php`
2. Akses: `https://api.diskominfo.sanggau.go.id/server-troubleshooting.php`
3. Check semua section, pastikan semua ✅
4. **HAPUS file** setelah selesai

### Check Specific Error

| Error | Solusi |
|-------|--------|
| 500 Error | Check `storage/logs/laravel.log` |
| Database Error | Verify kredensial di `.env` |
| 403 Error | Pastikan `Kernel.php` yang baru sudah ter-upload |
| Login Gagal | Clear browser cache & cookies |

---

## 📋 Perubahan yang Dilakukan

### File `.env` - Perubahan Kritis:

**SEBELUM (SALAH):**
```env
DB_HOST=127.0.0.1                # ❌ Tidak work di cPanel
DB_DATABASE=sanggau_db           # ❌ Database name salah
DB_USERNAME=root                 # ❌ Username salah
DB_PASSWORD=                     # ❌ Password kosong
```

**SESUDAH (BENAR):**
```env
DB_HOST=localhost                          # ✅ Benar untuk cPanel
DB_DATABASE=diskominfo_sanggaudb          # ✅ Database name benar
DB_USERNAME=diskominfo_sanggau            # ✅ Username benar
DB_PASSWORD=diskominfo_sanggau26          # ✅ Password benar
```

### File `app/Http/Kernel.php` - Middleware Fix:

**SEBELUM (BERMASALAH):**
```php
\App\Http\Middleware\DecodeBase64Input::class,  // ❌ Menyebabkan error 500
```

**SESUDAH (FIXED):**
```php
// \App\Http\Middleware\DecodeBase64Input::class,  // ✅ Disabled
```

---

## ⚡ Quick Command Reference

### Clear Cache Manual (jika clear-cache.php tidak work)

Via **cPanel Terminal** atau **SSH**:
```bash
cd /home/diskominfo/public_html  # Sesuaikan path
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

### Clear Cache Manual via File Manager

Hapus file-file ini:
```
bootstrap/cache/config.php
bootstrap/cache/routes-v7.php
bootstrap/cache/services.php
```

---

## ✅ Checklist Deployment

- [ ] File `app/Http/Kernel.php` sudah di-upload
- [ ] File `.env` sudah di-upload (dengan kredensial benar)
- [ ] File `clear-cache.php` sudah di-upload
- [ ] Akses clear-cache.php dan clear all cache
- [ ] Akses clear-cache.php dan rebuild cache
- [ ] File `clear-cache.php` sudah dihapus
- [ ] Test homepage - bisa dibuka
- [ ] Test login admin - berhasil
- [ ] Test buat berita - tidak ada error 403
- [ ] Test upload gambar - berhasil

---

## 🎉 Expected Result

Setelah deployment:

✅ Website accessible tanpa error 500  
✅ Login admin berfungsi normal  
✅ Database terkoneksi dengan benar  
✅ Buat/edit berita tidak error 403  
✅ Upload file berfungsi  
✅ Semua fitur CRUD normal  

---

## 📞 Jika Butuh Bantuan

1. **Check Error Log**: `storage/logs/laravel.log`
2. **Run Diagnostic**: Upload & akses `server-troubleshooting.php`
3. **Read Guide**: `TROUBLESHOOTING-GUIDE.md`

---

**Total waktu deployment: ~5 menit**  
**Kredensial database: SUDAH BENAR ✅**  
**Files ready: SIAP UPLOAD ✅**

**LET'S DEPLOY! 🚀**

---

**Created:** 12 Juni 2026  
**Status:** READY - Kredensial verified ✅
