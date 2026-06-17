# ✅ Deployment Checklist - Sanggau Backend

**Tanggal Deployment:** _______________  
**Dilakukan oleh:** _______________  
**Waktu Mulai:** _______________  
**Waktu Selesai:** _______________

---

## 📦 PRE-DEPLOYMENT

### Persiapan File
- [ ] File `app/Http/Kernel.php` sudah diperbaiki (DecodeBase64Input disabled)
- [ ] File `clear-cache.php` siap untuk di-upload (temporary)
- [ ] File `server-troubleshooting.php` siap untuk di-upload (temporary)
- [ ] Backup local sudah dibuat
- [ ] Dokumentasi sudah dibaca

### Persiapan cPanel
- [ ] Login ke cPanel berhasil
- [ ] Akses File Manager tersedia
- [ ] Akses MySQL Databases tersedia
- [ ] Backup database di-download via phpMyAdmin

---

## 🚀 DEPLOYMENT STEPS

### STEP 1: Upload File yang Diperbaiki
**Status:** [ ] Done | [ ] Error | [ ] Skip  
**Waktu:** _______________

- [ ] Upload `app/Http/Kernel.php` via File Manager atau FTP
- [ ] Verify file ter-upload dengan benar
- [ ] Check file size matches local file

**Notes:**
```
_________________________________________________________________
_________________________________________________________________
```

---

### STEP 2: Konfigurasi Database
**Status:** [ ] Done | [ ] Error | [ ] Skip  
**Waktu:** _______________

#### 2.1 Dapatkan Kredensial Database
- [ ] Buka cPanel → MySQL® Databases
- [ ] Catat nama database: `_______________________________`
- [ ] Catat username database: `_______________________________`
- [ ] Catat/verify password database (jangan tulis di sini!)

#### 2.2 Update File .env
- [ ] Buka file `.env` via File Manager (Edit)
- [ ] Update `DB_HOST=localhost`
- [ ] Update `DB_DATABASE=` dengan nama database dari step 2.1
- [ ] Update `DB_USERNAME=` dengan username dari step 2.1
- [ ] Update `DB_PASSWORD=` dengan password database
- [ ] Save file

#### 2.3 Verify .env Configuration
- [ ] Double-check tidak ada typo
- [ ] Pastikan tidak ada spasi di awal/akhir value
- [ ] Pastikan tidak ada karakter aneh (copy-paste issue)

**Notes:**
```
_________________________________________________________________
_________________________________________________________________
```

---

### STEP 3: Clear Cache
**Status:** [ ] Done | [ ] Error | [ ] Skip  
**Waktu:** _______________

**Metode yang digunakan:** [ ] Browser | [ ] SSH | [ ] Manual

#### Metode A: Via Browser (Recommended)
- [ ] Upload `clear-cache.php` ke root folder
- [ ] Akses URL: `https://api.diskominfo.sanggau.go.id/clear-cache.php`
- [ ] Klik button "Clear All Cache"
- [ ] Tunggu hingga selesai (check ✅ success messages)
- [ ] Klik button "Rebuild Cache"
- [ ] Tunggu hingga selesai
- [ ] **HAPUS file `clear-cache.php`** (PENTING!)

#### Metode B: Via SSH (Alternative)
- [ ] SSH ke server: `ssh user@host`
- [ ] CD ke folder: `cd path/to/app`
- [ ] Run: `php artisan config:clear`
- [ ] Run: `php artisan cache:clear`
- [ ] Run: `php artisan route:clear`
- [ ] Run: `php artisan view:clear`
- [ ] Run: `php artisan config:cache`
- [ ] Run: `php artisan route:cache`

#### Metode C: Manual (Last Resort)
- [ ] Via File Manager, hapus: `bootstrap/cache/config.php`
- [ ] Via File Manager, hapus: `bootstrap/cache/routes-v7.php`
- [ ] Via File Manager, hapus: `bootstrap/cache/services.php`

**Notes:**
```
_________________________________________________________________
_________________________________________________________________
```

---

### STEP 4: Verifikasi & Troubleshooting
**Status:** [ ] Done | [ ] Error | [ ] Skip  
**Waktu:** _______________

#### 4.1 Upload Troubleshooting Tool
- [ ] Upload `server-troubleshooting.php` ke root folder
- [ ] Akses URL: `https://api.diskominfo.sanggau.go.id/server-troubleshooting.php`

#### 4.2 Check Diagnostics
- [ ] ✅ PHP Version & Extensions (all green)
- [ ] ✅ File Permissions (all writable)
- [ ] ✅ .env Configuration (values correct)
- [ ] ✅ Database Connection (success)
- [ ] ✅ Laravel Cache (files exist or cleared)
- [ ] ✅ Logs (no critical errors)

#### 4.3 Cleanup
- [ ] **HAPUS file `server-troubleshooting.php`** (PENTING!)

**Error Messages (if any):**
```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

### STEP 5: Testing Fungsional
**Status:** [ ] Done | [ ] Error | [ ] Skip  
**Waktu:** _______________

#### 5.1 Test Frontend Public
- [ ] Akses: `https://diskominfo.sanggau.go.id`
- [ ] Homepage loading tanpa error
- [ ] Menu navigasi berfungsi
- [ ] Halaman berita dapat diakses
- [ ] Halaman galeri dapat diakses

#### 5.2 Test Admin Login
- [ ] Akses halaman login admin
- [ ] Login dengan kredensial admin
- [ ] Dashboard admin muncul tanpa error
- [ ] Menu sidebar dapat diakses

#### 5.3 Test CRUD Berita
- [ ] Buka halaman "Berita"
- [ ] List berita muncul dengan benar
- [ ] Klik "Tambah Berita"
- [ ] Isi form berita (judul, konten, gambar)
- [ ] Upload gambar berhasil
- [ ] Simpan berita berhasil (TIDAK ada error 403)
- [ ] Berita muncul di list
- [ ] Edit berita berhasil
- [ ] Delete berita berhasil (optional test)

#### 5.4 Test Feature Lainnya (Optional)
- [ ] Upload dokumen
- [ ] Manage banner
- [ ] Manage agenda
- [ ] Manage galeri
- [ ] Manage pengumuman

**Issues Found:**
```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

## 🔒 POST-DEPLOYMENT SECURITY

### Cleanup File Sensitif
- [ ] File `clear-cache.php` sudah dihapus
- [ ] File `server-troubleshooting.php` sudah dihapus
- [ ] File backup temporary sudah dihapus

### Security Settings
- [ ] `APP_DEBUG=false` di file `.env`
- [ ] `APP_ENV=production` di file `.env`
- [ ] File `.env` tidak ter-commit ke Git
- [ ] Error reporting disabled di production

### Permissions Check
- [ ] Folder `storage/` permission: 755 atau 775
- [ ] Folder `bootstrap/cache/` permission: 755 atau 775
- [ ] File `.env` permission: 644

---

## 📊 MONITORING (24 Jam Pertama)

### Jam ke-1
**Waktu Check:** _______________
- [ ] Website accessible
- [ ] No 500 errors
- [ ] Login works
- [ ] Check error logs

**Issues:** ___________________________________________________

---

### Jam ke-3
**Waktu Check:** _______________
- [ ] Website accessible
- [ ] No 500 errors
- [ ] Check error logs
- [ ] Check performance

**Issues:** ___________________________________________________

---

### Jam ke-24
**Waktu Check:** _______________
- [ ] Website accessible
- [ ] No 500 errors
- [ ] Check error logs
- [ ] Review user reports

**Issues:** ___________________________________________________

---

## 🚨 ROLLBACK PLAN (Jika Gagal)

### Rollback Steps
1. [ ] Restore database backup dari phpMyAdmin
2. [ ] Restore file `.env` dari backup
3. [ ] Restore file `Kernel.php` ke versi sebelumnya
4. [ ] Clear cache lagi
5. [ ] Test website

**Rollback Executed:** [ ] Yes | [ ] No  
**Waktu Rollback:** _______________  
**Reason:** ___________________________________________________

---

## 📝 CATATAN DEPLOYMENT

### Issues & Resolutions
```
Issue 1: _______________________________________________________
Resolution: ____________________________________________________

Issue 2: _______________________________________________________
Resolution: ____________________________________________________

Issue 3: _______________________________________________________
Resolution: ____________________________________________________
```

### Lessons Learned
```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

### Next Steps / Follow-up
```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

## ✅ SIGN OFF

**Deployment Status:** [ ] SUCCESS | [ ] PARTIAL | [ ] FAILED

**Developer Signature:** _______________  
**Date:** _______________

**Tester/QA Signature:** _______________  
**Date:** _______________

**Stakeholder Approval:** _______________  
**Date:** _______________

---

## 📞 KONTAK EMERGENCY

| Role | Nama | Kontak |
|------|------|--------|
| Developer | _____________ | _____________ |
| Server Admin | _____________ | _____________ |
| Hosting Support | _____________ | _____________ |
| Project Manager | _____________ | _____________ |

---

**File ini dibuat:** 12 Juni 2026  
**Version:** 1.0  
**Last Updated:** 12 Juni 2026
