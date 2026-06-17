# 📤 UPLOAD TANPA TERMINAL - File Manager Only

## Deploy Backend TANPA Terminal, TANPA SSH, TANPA Composer Command

**Cara ini untuk cPanel yang tidak ada Terminal/SSH access.**

---

## ⚠️ PENTING - BACA DULU!

Karena tidak ada Terminal, kita **SKIP** command composer. Tapi kita akan:
1. ✅ Upload semua file yang sudah diubah
2. ✅ Buat folder Helpers manual
3. ✅ Edit composer.json agar autoload Helpers
4. ✅ **Upload folder vendor yang sudah di-generate di local**

---

## 📋 PERSIAPAN DI LOCAL (PC Anda)

### STEP 1: Generate vendor di Local

Sebelum upload, kita generate vendor di local dulu:

```bash
# Di PC, buka Command Prompt atau PowerShell
cd C:\xampp\htdocs\sanggau-backend

# Generate vendor dengan production setting
composer install --no-dev --optimize-autoloader

# Dump autoload agar Helpers ke-load
composer dump-autoload -o
```

Setelah ini, folder `vendor/` di local sudah updated dan include autoload untuk Helpers.

### STEP 2: Compress Folder vendor

Karena folder vendor besar (puluhan ribu files), compress dulu:

```bash
# Di folder sanggau-backend, compress vendor
# Windows: Klik kanan vendor/ → Send to → Compressed (zipped) folder
# Atau gunakan 7zip/WinRAR

Nama file: vendor.zip
```

---

## 📤 UPLOAD KE cPanel (File Manager Only)

### STEP 1: Upload File-file yang Diubah

Upload files ini via File Manager seperti biasa:

#### A. app/Models/ (3 files)
1. Masuk folder `app/Models/`
2. Backup existing files (copy → rename .backup)
3. Delete files lama
4. Upload files baru:
   - `Berita.php`
   - `Banner.php`
   - `Galeri.php`

#### B. routes/api.php
1. Masuk folder `routes/`
2. Backup `api.php` → `api.php.backup`
3. Delete `api.php`
4. Upload `api.php` baru

#### C. composer.json
1. Di root folder (public_html)
2. Backup `composer.json` → `composer.json.backup`
3. Delete `composer.json`
4. Upload `composer.json` baru

---

### STEP 2: Buat Folder Helpers & Upload helpers.php

1. **Masuk folder `app/`**
2. **Klik tombol "+ Folder"** (di toolbar atas)
3. **Ketik:** `Helpers`
4. **Klik "Create New Folder"**
5. **Klik folder `Helpers/`** untuk masuk
6. **Klik "Upload"**
7. **Select file:** `C:\xampp\htdocs\sanggau-backend\app\Helpers\helpers.php`
8. **Upload** → tunggu selesai
9. **Klik "Go Back"**

---

### STEP 3: Upload vendor.zip & Extract

**⚠️ PENTING: Ini step paling penting!**

#### A. Backup vendor Lama (Opsional tapi Disarankan)

1. Di root folder (public_html)
2. Klik kanan folder `vendor/`
3. Pilih **"Compress"**
4. Format: ZIP
5. Nama: `vendor_backup.zip`
6. Compression Level: Fastest
7. Klik **"Compress File(s)"**
8. Tunggu sampai selesai (bisa 5-10 menit)

#### B. Delete vendor Lama

1. Klik kanan folder `vendor/`
2. Pilih **"Delete"**
3. Confirm **"Delete Files"**
4. Tunggu sampai selesai

#### C. Upload vendor.zip Baru

1. Klik tombol **"Upload"** di toolbar
2. **Select File:** `C:\xampp\htdocs\sanggau-backend\vendor.zip`
3. **Upload** → tunggu sampai selesai
   - ⏳ Bisa 10-30 menit tergantung ukuran & internet
   - Jangan close browser saat upload!
4. Setelah selesai, klik **"Go Back"**

#### D. Extract vendor.zip

1. Di root folder (public_html)
2. **Klik kanan `vendor.zip`**
3. Pilih **"Extract"**
4. Path: `/home/username/public_html` (atau path root Anda)
5. Klik **"Extract File(s)"**
6. Tunggu sampai selesai (bisa 5-10 menit)
7. Verify ada folder `vendor/` dengan banyak subfolder

#### E. Delete vendor.zip (Cleanup)

1. Klik kanan `vendor.zip`
2. Delete

---

### STEP 4: Update .env

1. **Klik kanan `.env`**
2. Pilih **"Edit"**
3. **Update baris ini:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://diskominfo.sanggau.go.id
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=your_database_name
   DB_USERNAME=your_db_username
   DB_PASSWORD=your_db_password
   ```
4. **Save Changes**

---

### STEP 5: Set Permissions (Optional tapi Recommended)

Jika ada menu Permissions:

1. Klik kanan folder `storage/`
2. **"Change Permissions"**
3. Set: **0777** (atau tick all boxes)
4. Tick **"Recurse into subdirectories"**
5. Apply

Ulangi untuk:
- `bootstrap/cache/` → 0755
- `public/uploads/` → 0777

---

## 🧪 TESTING (Tanpa Terminal)

### Test 1: Check via Browser

**Buka di browser:**
```
https://diskominfo.sanggau.go.id/api/banner
```

**Expected Output:**
```json
[
  {
    "id": 1,
    "judul": "Banner Test",
    "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
  }
]
```

✅ **Jika `gambar` adalah FULL URL** → SUCCESS!
❌ **Jika `gambar` masih `/uploads/...`** → Ada masalah

### Test 2: Upload Gambar di CMS

1. Login CMS admin
2. Tambah berita baru
3. Upload gambar
4. Simpan
5. Buka frontend → lihat apakah gambar muncul

### Test 3: Check Admin Permissions

1. Login sebagai **admin**
2. Coba akses menu:
   - ✅ Banner (harus bisa)
   - ✅ Galeri (harus bisa)
   - ✅ Statistik (harus bisa)
   - ❌ Pengguna (harus TIDAK bisa - 403)

---

## 🚨 TROUBLESHOOTING (Tanpa Terminal)

### Problem: API Masih Return Relative Path

**Solusi:**

1. **Verify vendor/ terupload & terextract dengan benar**
   - Check folder `vendor/composer/`
   - Harus ada file `autoload_files.php`
   - Buka file tersebut (Edit)
   - Cari baris yang include `app/Helpers/helpers.php`

2. **Verify Helpers folder & file ada**
   - Check `app/Helpers/helpers.php` exists
   - File size harus ~1-2 KB (bukan 0 KB)

3. **Verify composer.json sudah updated**
   - Buka `composer.json` (Edit)
   - Cari section `"autoload"`
   - Harus ada `"files": ["app/Helpers/helpers.php"]`

4. **Clear browser cache**
   - Ctrl+Shift+Delete
   - Clear cache
   - Refresh page

5. **Force refresh API:**
   - Buka: `https://diskominfo.sanggau.go.id/api/banner?_t=12345`
   - Tambah parameter `?_t=` dengan timestamp random

### Problem: Upload vendor.zip Gagal

**Solusi A: Upload via FTP (Jika ada akses)**

1. Download FileZilla Client
2. Connect ke FTP server
3. Upload `vendor.zip`
4. Extract via File Manager cPanel

**Solusi B: Split vendor jadi beberapa zip**

Jika vendor.zip terlalu besar (>500MB):

1. Di local, split folder vendor jadi beberapa bagian:
   ```
   vendor/
   ├── laravel/     → zip jadi vendor_laravel.zip
   ├── symfony/     → zip jadi vendor_symfony.zip
   ├── composer/    → zip jadi vendor_composer.zip
   └── ...
   ```

2. Upload satu per satu
3. Extract satu per satu ke folder vendor/

**Solusi C: Compress dengan higher compression**

1. Gunakan 7zip atau WinRAR
2. Set compression level: "Ultra" atau "Maximum"
3. Format: .zip atau .tar.gz
4. Upload hasil compress

---

## 📋 CHECKLIST UPLOAD TANPA TERMINAL

### Files Uploaded:
- [ ] ✅ app/Models/Berita.php
- [ ] ✅ app/Models/Banner.php
- [ ] ✅ app/Models/Galeri.php
- [ ] ✅ app/Helpers/helpers.php (folder baru!)
- [ ] ✅ routes/api.php
- [ ] ✅ composer.json
- [ ] ✅ vendor/ (uploaded & extracted)
- [ ] ✅ .env (edited)

### Verification:
- [ ] ✅ Folder Helpers/ exists di app/
- [ ] ✅ File helpers.php ada di app/Helpers/
- [ ] ✅ Folder vendor/ terupdate (check timestamp)
- [ ] ✅ File vendor/composer/autoload_files.php include Helpers
- [ ] ✅ .env APP_URL sudah production URL

### Testing:
- [ ] ✅ API banner return full URL
- [ ] ✅ Upload gambar di CMS
- [ ] ✅ Gambar muncul di frontend
- [ ] ✅ Admin bisa akses banner/statistik
- [ ] ✅ Admin TIDAK bisa akses pengguna

---

## 🎯 SUMMARY

**Karena TIDAK ADA Terminal, workflow-nya:**

1. ✅ Generate vendor di local (composer install)
2. ✅ Compress vendor → vendor.zip
3. ✅ Upload files yang diubah (Models, routes, composer.json)
4. ✅ Buat folder Helpers, upload helpers.php
5. ✅ Upload vendor.zip → Extract
6. ✅ Edit .env
7. ✅ Test API → harus return full URL

**Kelebihan cara ini:**
- ✅ Tidak perlu Terminal/SSH
- ✅ Tidak perlu akses composer di server
- ✅ Semua dilakukan via File Manager browser

**Kekurangan:**
- ⏳ Upload vendor.zip bisa lama (10-30 menit)
- 💾 Perlu bandwidth besar untuk upload vendor

---

## ⚡ TIPS

1. **Upload saat traffic rendah** (malam hari)
2. **Gunakan koneksi internet stabil** (kabel LAN lebih baik dari WiFi)
3. **Jangan close browser** saat upload vendor.zip
4. **Backup dulu** sebelum delete vendor lama
5. **Verify file size** setelah upload (tidak 0 KB)

---

## ✅ SELESAI!

Jika semua checklist ✅, backend sudah updated tanpa perlu Terminal!

**Next:** Deploy frontend via Git (lihat GIT-COMMIT-GUIDE.md)

---

**Good Luck! 🚀**
