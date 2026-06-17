# ✅ CHECKLIST FIX BANNER & ADMIN - LANGKAH CEPAT

## 🎯 MASALAH YANG HARUS DIFIX:

1. ❌ API `/api/banner` return 404
2. ❌ Gambar banner tidak muncul di homepage
3. ❌ Admin tidak bisa update data di CMS

---

## 📋 LANGKAH-LANGKAH (URUT!)

### **STEP 1: Set Document Root di cPanel** ⭐ PALING PENTING!

**Kenapa:** Ini penyebab utama API 404!

**Cara:**
1. Login cPanel → Menu "Domains"
2. Klik "Manage" di domain `diskominfo.sanggau.go.id`
3. Edit "Document Root":
   - Dari: `/home/diskominfo/public_html`
   - Jadi: `/home/diskominfo/public_html/public`
4. Save Changes
5. Tunggu 1-2 menit

**Test:**
```
https://diskominfo.sanggau.go.id/api/banner
```

✅ **Jika muncul JSON** → Lanjut STEP 2
❌ **Jika masih 404** → Baca: `CARA-SET-DOCUMENT-ROOT-CPANEL.md`

---

### **STEP 2: Upload Files yang Sudah Difix**

**Files di local yang WAJIB upload:**

```
C:\xampp\htdocs\sanggau-backend\

✅ app\Models\Banner.php
✅ app\Models\Berita.php
✅ app\Models\Galeri.php
✅ app\Helpers\helpers.php     ← BUAT FOLDER app/Helpers/ DULU!
✅ routes\api.php
✅ composer.json
```

**Upload ke cPanel:**

```
/home/diskominfo/public_html/

app/Models/Banner.php
app/Models/Berita.php
app/Models/Galeri.php
app/Helpers/helpers.php        ← Buat folder Helpers/ dulu!
routes/api.php
composer.json
```

**Cara Upload:**
1. cPanel → File Manager
2. Navigate ke folder tujuan
3. Backup file lama (copy → rename .backup)
4. Delete file lama
5. Klik "Upload"
6. Select file dari PC
7. Tunggu selesai → "Go Back"

**Khusus untuk `app/Helpers/helpers.php`:**
1. Masuk folder `app/`
2. Klik "+ Folder"
3. Nama: `Helpers`
4. Masuk folder `Helpers/`
5. Upload file `helpers.php`

---

### **STEP 3: Upload & Extract vendor.zip** (OPTIONAL)

**⚠️ HANYA jika Anda belum pernah upload vendor atau vendor lama!**

**Persiapan di PC:**
1. Buka Command Prompt
2. Jalankan:
   ```bash
   cd C:\xampp\htdocs\sanggau-backend
   composer install --no-dev --optimize-autoloader
   composer dump-autoload -o
   ```
3. Compress folder `vendor/` → `vendor.zip`

**Upload ke cPanel:**
1. Upload `vendor.zip` ke `/home/diskominfo/public_html/`
2. Backup `vendor/` lama (compress → `vendor_backup.zip`)
3. Delete folder `vendor/` lama
4. Extract `vendor.zip` (klik kanan → Extract)
5. Tunggu 5-10 menit
6. Delete `vendor.zip`

---

### **STEP 4: Edit .env di cPanel**

**File:** `/home/diskominfo/public_html/.env`

**Pastikan setting ini BENAR:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://diskominfo.sanggau.go.id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=diskominfo_sanggaudb
DB_USERNAME=(isi username database Anda)
DB_PASSWORD=(isi password database Anda)
```

**Cara Edit:**
1. cPanel File Manager
2. Klik kanan `.env`
3. Edit
4. Update baris yang perlu
5. Save Changes

---

### **STEP 5: Test Semua Fitur**

#### **Test 1: API Banner Return Full URL**

Buka di browser:
```
https://diskominfo.sanggau.go.id/api/banner
```

**Expected:**
```json
[
  {
    "id": 1,
    "judul": "Banner Test",
    "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
  }
]
```

✅ Field `gambar` harus **FULL URL** (https://...)
❌ Jika masih `/uploads/...` → .env APP_URL salah atau vendor belum update

---

#### **Test 2: Upload Banner Baru di CMS**

1. Login CMS sebagai **admin** (bukan superadmin)
2. Menu **Banner** → Tambah Baru
3. Isi form + upload gambar
4. Simpan
5. Buka homepage frontend
6. **Verify:** Banner baru muncul dengan gambar

✅ Jika muncul → SUCCESS!
❌ Jika tidak muncul → Check:
   - API return full URL?
   - File gambar ada di `/public_html/public/uploads/banner/`?
   - Gambar URL bisa diakses langsung?

---

#### **Test 3: Admin Permissions**

**Login sebagai Admin:**
1. Coba akses menu:
   - ✅ Banner → Harus bisa (create, update, delete)
   - ✅ Galeri → Harus bisa
   - ✅ Berita → Harus bisa
   - ✅ Statistik → Harus bisa
   - ✅ Agenda → Harus bisa
   - ❌ Pengguna → Harus TIDAK bisa (403 Forbidden)

**Login sebagai Superadmin:**
1. Coba akses menu:
   - ✅ Semua menu harus bisa (termasuk Pengguna)

---

## 📋 FINAL CHECKLIST

### Document Root:
- [ ] ✅ Document Root sudah `/home/diskominfo/public_html/public`
- [ ] ✅ API `/api/banner` return JSON (bukan 404)

### Files Uploaded:
- [ ] ✅ app/Models/Banner.php
- [ ] ✅ app/Models/Berita.php
- [ ] ✅ app/Models/Galeri.php
- [ ] ✅ app/Helpers/helpers.php (folder Helpers/ dibuat)
- [ ] ✅ routes/api.php
- [ ] ✅ composer.json
- [ ] ✅ vendor/ (uploaded & extracted, jika perlu)

### Configuration:
- [ ] ✅ .env APP_URL = https://diskominfo.sanggau.go.id
- [ ] ✅ .env DB credentials benar
- [ ] ✅ File timestamps hari ini (verify terupload)

### Testing:
- [ ] ✅ API banner return full URL (https://...)
- [ ] ✅ Upload banner baru di CMS berhasil
- [ ] ✅ Banner muncul di homepage dengan gambar
- [ ] ✅ Admin bisa update banner/berita/galeri
- [ ] ✅ Admin TIDAK bisa akses menu Pengguna
- [ ] ✅ Superadmin bisa akses semua menu

---

## ⏱️ ESTIMASI WAKTU

```
STEP 1: Set Document Root     →  5 menit
STEP 2: Upload 6 files         → 10 menit
STEP 3: Upload vendor (opt)    → 30 menit (optional)
STEP 4: Edit .env              →  2 menit
STEP 5: Testing                →  5 menit
─────────────────────────────────────────
TOTAL:                           22-52 menit
```

---

## 🚨 TROUBLESHOOTING CEPAT

### Problem: API masih 404

**Solusi:**
1. Verify Document Root sudah benar
2. Check file `public/index.php` exists
3. Check `.htaccess` di `public/` exists
4. Test: `https://diskominfo.sanggau.go.id/index.php/api/banner`

---

### Problem: API return JSON tapi gambar masih relative path

**Solusi:**
1. Verify Banner.php sudah terupload (check timestamp)
2. Verify .env APP_URL benar
3. Verify vendor/ sudah update (autoload Helpers)
4. Clear browser cache (Ctrl+Shift+Delete)

---

### Problem: Admin masih tidak bisa update

**Solusi:**
1. Verify routes/api.php sudah terupload
2. Check file timestamp (harus hari ini)
3. Login ulang di CMS (logout → login)
4. Check role user di database (harus `admin`)

---

### Problem: Upload vendor.zip gagal (terlalu besar)

**Solusi A:** Upload via FTP (FileZilla)

**Solusi B:** Split vendor jadi beberapa zip

**Solusi C:** Tidak usah upload vendor, tapi:
1. Edit `vendor/composer/autoload_files.php` manual
2. Tambahkan baris:
   ```php
   $baseDir . '/app/Helpers/helpers.php',
   ```

---

## 🎉 SELESAI!

Jika semua checklist ✅:
- ✅ API sudah jalan (tidak 404)
- ✅ Banner muncul dengan gambar full URL
- ✅ Admin bisa update semua konten
- ✅ Permission sudah benar

**Next Step:**
- Deploy frontend via Vercel/Git
- Training user admin cara pakai CMS

---

## 📚 DOKUMENTASI LENGKAP

Jika ada masalah, baca dokumentasi lengkap:

1. `CARA-SET-DOCUMENT-ROOT-CPANEL.md` - Cara set Document Root
2. `FIX-API-404-SEKARANG.md` - Troubleshooting API 404
3. `STRUKTUR-FOLDER-CPANEL.md` - Penjelasan struktur folder
4. `UPLOAD-TANPA-TERMINAL.md` - Cara upload tanpa SSH
5. `FINAL-UPLOAD-SEKARANG.md` - Panduan upload files

---

**MULAI DARI STEP 1 SEKARANG! 🚀**

Set Document Root dulu → Test API → Upload files → Test upload gambar!

**Good Luck! 🎉**
