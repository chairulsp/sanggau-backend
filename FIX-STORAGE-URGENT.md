# 🚨 FIX STORAGE STRUCTURE - URGENT

## ❌ Masalah Ditemukan:

Folder **`storage/framework`** TIDAK ADA di server!

Laravel **TIDAK BISA JALAN** tanpa folder ini. Ini menyebabkan:
- ❌ Error: "Target class [db] does not exist"
- ❌ Error: "Please provide a valid cache path"
- ❌ Website error 500

---

## ✅ SOLUSI CEPAT (2 Menit):

### OPSI A: Upload Script Auto-Fix (RECOMMENDED)

1. **Upload file** `fix-storage-structure.php` ke root folder cPanel

2. **Akses via browser:**
   ```
   https://api.diskominfo.sanggau.go.id/fix-storage-structure.php
   ```

3. Script akan otomatis membuat semua folder yang dibutuhkan:
   - `storage/framework`
   - `storage/framework/cache`
   - `storage/framework/cache/data`
   - `storage/framework/sessions`
   - `storage/framework/views`
   - Dan folder lainnya

4. **HAPUS file** `fix-storage-structure.php` setelah selesai!

5. **Clear cache lagi** dengan `clear-cache.php`

6. **Test website**

---

### OPSI B: Manual via File Manager

Jika script tidak work, buat folder manual via **cPanel File Manager**:

```
storage/framework/              → Permission: 755
storage/framework/cache/        → Permission: 755
storage/framework/cache/data/   → Permission: 755
storage/framework/sessions/     → Permission: 755
storage/framework/testing/      → Permission: 755
storage/framework/views/        → Permission: 755
```

**Cara buat folder:**
1. Masuk ke folder `storage/`
2. Klik **"+ Folder"**
3. Nama: `framework`
4. Masuk ke `framework/`, buat subfolder: `cache`, `sessions`, `views`, `testing`
5. Masuk ke `cache/`, buat subfolder: `data`
6. Set permission semua folder ke **755**

---

### OPSI C: Via SSH Terminal (Jika Ada Akses)

```bash
cd /home/diskominfo/public_html

# Buat semua folder sekaligus
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/testing
mkdir -p storage/framework/views

# Set permissions
chmod -R 755 storage/framework
chmod -R 755 bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## 📋 Struktur Folder yang Dibutuhkan:

```
storage/
├── app/
│   └── public/
├── framework/           ← MISSING! (Ini yang hilang)
│   ├── cache/
│   │   └── data/
│   ├── sessions/
│   ├── testing/
│   └── views/
└── logs/
```

---

## ✅ Verifikasi Setelah Fix:

Upload dan akses lagi: `server-troubleshooting.php`

Check section **"File & Folder Permissions"**:
- ✅ storage/framework → harus muncul
- ✅ Database Connection Test → harus ✅

---

## 🎯 Langkah Lengkap:

1. **Upload & Run:** `fix-storage-structure.php`
2. **Verify:** Check semua folder terbuat
3. **Delete:** `fix-storage-structure.php`
4. **Clear Cache:** Akses `clear-cache.php` → Clear All
5. **Verify:** Upload & akses `server-troubleshooting.php`
6. **Test:** Buka website & test login

---

## ⚠️ Mengapa Folder Ini Hilang?

Kemungkinan:
- Antigravity tidak upload folder kosong
- Folder ter-exclude saat upload
- .gitignore memblok folder (folder kosong tidak di-commit ke Git)

**Solusi permanent:** Selalu pastikan struktur folder lengkap saat deployment.

---

## 📞 Jika Masih Error:

1. Check permissions semua folder storage (harus 755 atau 775)
2. Check ownership folder (harus owned by web server user)
3. Check error log: `storage/logs/laravel.log`

---

**Status:** CRITICAL - Website tidak bisa jalan tanpa fix ini  
**Priority:** HIGH  
**Estimated Time:** 2 menit

**FIX THIS FIRST BEFORE ANYTHING ELSE!** 🚨
