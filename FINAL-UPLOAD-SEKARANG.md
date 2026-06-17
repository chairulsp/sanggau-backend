# 🚀 UPLOAD SEKARANG - FILES SUDAH FIXED!

## ✅ SEMUA MASALAH SUDAH DIPERBAIKI!

### **Yang Sudah Diperbaiki:**
1. ✅ Banner.php - Hapus default localhost, gunakan request URL sebagai fallback
2. ✅ Berita.php - Sama seperti Banner
3. ✅ Galeri.php - Sama seperti Banner
4. ✅ Admin permissions - Sudah bisa update semua (kecuali user management)
5. ✅ Vendor sudah di-generate ulang
6. ✅ Cache sudah di-clear

---

## 📤 UPLOAD FILES INI KE cPanel (WAJIB!)

### **Location di PC:**
```
C:\xampp\htdocs\sanggau-backend\
```

### **Files yang WAJIB Upload:**

```
✅ app\Models\Banner.php        ← UPDATED (tanpa localhost default)
✅ app\Models\Berita.php        ← UPDATED (tanpa localhost default)
✅ app\Models\Galeri.php        ← UPDATED (tanpa localhost default)
✅ app\Helpers\helpers.php      ← BUAT FOLDER BARU
✅ routes\api.php               ← UPDATED (admin permissions)
✅ composer.json                ← UPDATED (autoload helpers)
```

**OPTIONAL (jika belum upload):**
```
✅ vendor.zip                   ← Compress vendor/ folder
```

---

## 🎯 CARA UPLOAD (STEP BY STEP)

### **STEP 1: Login cPanel**
```
URL: https://sanggau.go.id:2083/
atau: https://diskominfo.sanggau.go.id:2083/

Klik: File Manager
Navigate ke: public_html
```

---

### **STEP 2: Upload app/Models/Banner.php**

1. **Navigate:** `app/Models/`
2. **Backup old file:**
   - Klik kanan `Banner.php`
   - Copy → Paste
   - Rename: `Banner.php.backup`
3. **Delete old:**
   - Delete `Banner.php`
4. **Upload new:**
   - Klik **Upload**
   - Select: `C:\xampp\htdocs\sanggau-backend\app\Models\Banner.php`
   - Upload → Go Back
5. **Verify:**
   - File size: ~2-3 KB
   - Timestamp: Hari ini

---

### **STEP 3: Upload app/Models/Berita.php**

**Repeat langkah yang sama:**
1. Navigate: `app/Models/`
2. Backup: `Berita.php` → `Berita.php.backup`
3. Delete: `Berita.php`
4. Upload: `C:\xampp\htdocs\sanggau-backend\app\Models\Berita.php`

---

### **STEP 4: Upload app/Models/Galeri.php**

**Repeat langkah yang sama:**
1. Navigate: `app/Models/`
2. Backup: `Galeri.php` → `Galeri.php.backup`
3. Delete: `Galeri.php`
4. Upload: `C:\xampp\htdocs\sanggau-backend\app\Models\Galeri.php`

---

### **STEP 5: Buat Folder Helpers & Upload helpers.php**

**⚠️ PENTING: Folder Helpers belum ada, harus dibuat!**

1. Navigate: `app/`
2. **Klik tombol "+ Folder"**
3. Ketik: `Helpers`
4. Create
5. **Masuk folder Helpers/**
6. **Upload:** `C:\xampp\htdocs\sanggau-backend\app\Helpers\helpers.php`
7. Go Back

---

### **STEP 6: Upload routes/api.php**

1. Navigate: `routes/`
2. Backup: `api.php` → `api.php.backup`
3. Delete: `api.php`
4. Upload: `C:\xampp\htdocs\sanggau-backend\routes\api.php`

---

### **STEP 7: Upload composer.json**

1. Navigate: root folder (public_html)
2. Backup: `composer.json` → `composer.json.backup`
3. Delete: `composer.json`
4. Upload: `C:\xampp\htdocs\sanggau-backend\composer.json`

---

### **STEP 8: Upload vendor (JIKA BELUM)**

**⚠️ HANYA jika vendor di cPanel belum update!**

#### A. Compress vendor di Local (jika belum):
```
Windows Explorer:
C:\xampp\htdocs\sanggau-backend\
Klik kanan folder "vendor"
Send to → Compressed (zipped) folder
Tunggu selesai → vendor.zip
```

#### B. Upload & Extract:
1. cPanel File Manager root (public_html)
2. **Backup vendor lama** (optional):
   - Klik kanan `vendor/`
   - Compress → vendor_backup.zip
3. **Delete vendor lama**
4. **Upload vendor.zip**
   - ⏳ Tunggu 10-30 menit
5. **Extract vendor.zip**
   - Klik kanan vendor.zip
   - Extract → Extract to: (path current)
   - Tunggu 5-10 menit
6. **Delete vendor.zip** (cleanup)

---

### **STEP 9: Update .env (PENTING!)**

**⚠️ INI YANG PALING PENTING!**

1. **Klik kanan `.env`**
2. **Edit**
3. **Update baris ini:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://diskominfo.sanggau.go.id
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   ```
4. **Save Changes**

---

## 🧪 TESTING (SANGAT PENTING!)

### **Test 1: API Banner**

Buka di browser:
```
https://diskominfo.sanggau.go.id/api/banner
```

**Expected Output:**
```json
[
  {
    "id": 1,
    "judul": "Selamat Datang di Kabupaten Sanggau",
    "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
  }
]
```

✅ **Jika `gambar` adalah FULL URL** → SUCCESS!
❌ **Jika masih `/uploads/...`** → .env APP_URL belum diupdate

---

### **Test 2: Upload Banner Baru di CMS**

1. **Login CMS admin**
2. **Banner → Tambah Baru**
3. **Upload gambar**
4. **Simpan**
5. **Refresh frontend homepage**
6. **Verify:** Banner baru muncul dengan gambar

---

### **Test 3: Admin Permissions**

**Login sebagai Admin:**
1. Coba akses: **Banner** → Harus bisa ✅
2. Coba akses: **Galeri** → Harus bisa ✅
3. Coba akses: **Statistik** → Harus bisa ✅
4. Coba akses: **Pengguna** → Harus TIDAK bisa ❌ (403)

**Login sebagai Superadmin:**
1. Coba akses: **Pengguna** → Harus bisa ✅

---

## 📋 CHECKLIST UPLOAD

### Files Uploaded:
- [ ] ✅ app/Models/Banner.php (backup → delete → upload)
- [ ] ✅ app/Models/Berita.php (backup → delete → upload)
- [ ] ✅ app/Models/Galeri.php (backup → delete → upload)
- [ ] ✅ app/Helpers/helpers.php (buat folder Helpers → upload)
- [ ] ✅ routes/api.php (backup → delete → upload)
- [ ] ✅ composer.json (backup → delete → upload)
- [ ] ✅ vendor/ (upload vendor.zip → extract → delete zip)
- [ ] ✅ .env (edit APP_URL & DB credentials)

### Verification:
- [ ] ✅ File timestamps hari ini
- [ ] ✅ File sizes masuk akal (tidak 0 KB)
- [ ] ✅ Folder Helpers/ exists
- [ ] ✅ .env APP_URL = https://diskominfo.sanggau.go.id

### Testing:
- [ ] ✅ API banner return full URL
- [ ] ✅ Upload banner baru di CMS
- [ ] ✅ Banner muncul di homepage
- [ ] ✅ Admin bisa update banner
- [ ] ✅ Admin TIDAK bisa akses pengguna

---

## ⚠️ TROUBLESHOOTING

### Gambar Masih Tidak Muncul

**Solusi 1: Check .env**
```env
# Pastikan ini:
APP_URL=https://diskominfo.sanggau.go.id

# BUKAN ini:
APP_URL=http://localhost:8000
```

**Solusi 2: Verify file terupload**
- Buka `app/Models/Banner.php` di cPanel
- Edit → Cari `getSchemeAndHttpHost()`
- Harus ada baris tersebut

**Solusi 3: Clear cache (jika ada Terminal)**
```bash
cd public_html
php artisan config:clear
php artisan cache:clear
```

**Solusi 4: Wait 5-10 menit**
- Cache auto-expire
- Atau force refresh: `?nocache=123` di URL

---

### Admin Tidak Bisa Update

**Solusi: Check routes/api.php**
1. Buka `routes/api.php` di cPanel
2. Edit → Cari section "role:admin"
3. Banner, Galeri, dll harus di section `role:admin`
4. **BUKAN** di section `role:superadmin`

---

## 🎉 SELESAI!

Jika semua checklist ✅:
- ✅ Backend sudah updated
- ✅ Gambar akan muncul
- ✅ Admin bisa update
- ✅ Permission sudah benar

**Next:** Deploy frontend via Git
→ Lihat: sanggau-frontend/GIT-COMMIT-GUIDE.md

---

## ⏱️ ESTIMASI WAKTU:

```
Upload files (kecil):      5-10 menit
Upload vendor.zip:         10-30 menit (optional)
Extract vendor:            5-10 menit (optional)
Edit .env:                 2 menit
Testing:                   5 menit
──────────────────────────────────
TOTAL:                     15-60 menit
```

---

**MULAI SEKARANG!** 🚀

**Step 1:** Login cPanel
**Step 2:** Upload 3 Models (Banner, Berita, Galeri)
**Step 3:** Buat folder Helpers, upload helpers.php
**Step 4:** Upload routes/api.php & composer.json
**Step 5:** Edit .env (APP_URL)
**Step 6:** Test API!

**Good Luck!** 🎉
