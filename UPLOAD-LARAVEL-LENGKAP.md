# 📦 UPLOAD LARAVEL BACKEND LENGKAP KE cPanel

## ⚠️ PENTING!

Laravel backend **BELUM ADA** di server cPanel!
File `artisan` tidak ditemukan = Laravel belum pernah diupload.

Kita harus upload **SELURUH PROJECT** Laravel (bukan cuma 6 file).

---

## 📋 PERSIAPAN DI LOCAL (PC ANDA)

### **STEP 1: Bersihkan & Optimize Project**

Buka **Command Prompt** atau **PowerShell**:

```bash
cd C:\xampp\htdocs\sanggau-backend

# Clear cache local
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate production vendor (tanpa dev dependencies)
composer install --no-dev --optimize-autoloader

# Dump autoload
composer dump-autoload -o
```

---

### **STEP 2: Update .env untuk Production**

Edit file `C:\xampp\htdocs\sanggau-backend\.env`:

```env
APP_NAME="Website Kabupaten Sanggau"
APP_ENV=production
APP_KEY=base64:8jNXeaVtoLJIQwIg6d6bneycgF+bSB/qELglQrggxDM=
APP_DEBUG=false
APP_URL=https://diskominfo.sanggau.go.id

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=diskominfo_sanggaudb
DB_USERNAME=YOUR_DB_USERNAME
DB_PASSWORD=YOUR_DB_PASSWORD

# ... (sisanya sama)
```

**⚠️ GANTI:**
- `DB_DATABASE` = nama database di cPanel
- `DB_USERNAME` = username database di cPanel
- `DB_PASSWORD` = password database di cPanel

---

### **STEP 3: Compress Project Laravel (TANPA folder-folder besar)**

**JANGAN** compress folder ini (terlalu besar):
- ❌ `node_modules/` (ini untuk frontend, bukan Laravel)
- ❌ `sanggau-frontend/` (frontend terpisah)
- ❌ `.git/` (git history tidak perlu)
- ✅ `vendor/` (INI PERLU! Jangan di-exclude!)

**Cara Compress:**

#### **Option A: Via Windows Explorer (Mudah)**

1. Buka Windows Explorer
2. Navigate ke `C:\xampp\htdocs\sanggau-backend\`
3. **Pilih folder/files INI:**
   - ✅ `app/`
   - ✅ `bootstrap/`
   - ✅ `config/`
   - ✅ `database/`
   - ✅ `public/`
   - ✅ `resources/`
   - ✅ `routes/`
   - ✅ `storage/`
   - ✅ `vendor/`
   - ✅ File `.env`
   - ✅ File `.htaccess` (jika ada)
   - ✅ File `artisan`
   - ✅ File `composer.json`
   - ✅ File `composer.lock`
   - ✅ File `package.json` (optional)

4. **Klik kanan → Send to → Compressed (zipped) folder**
5. **Nama file:** `laravel-backend.zip`
6. **Tunggu hingga selesai** (5-15 menit tergantung ukuran vendor)

#### **Option B: Via 7zip / WinRAR (Lebih Cepat)**

1. Install 7zip atau WinRAR
2. Klik kanan folder `sanggau-backend`
3. **Pilih "Add to archive..."**
4. **Exclude:**
   - `node_modules/`
   - `sanggau-frontend/`
   - `.git/`
5. **Compression:** Normal atau Fast
6. **Create**

---

### **STEP 4: Verify ZIP Size**

**Expected size:** ~50-150 MB (tergantung vendor)

**⚠️ Jika lebih dari 500 MB:**
- Kemungkinan `node_modules/` atau `.git/` ikut ter-compress
- Extract ulang, exclude folder tersebut, compress lagi

---

## 📤 UPLOAD KE cPanel

### **STEP 1: Login cPanel File Manager**

```
URL: https://diskominfo.sanggau.go.id:2083/
atau: https://sanggau.go.id:2083/
```

---

### **STEP 2: Tentukan Lokasi Upload**

**⚠️ PENTING:** Berdasarkan setup Anda, ada 2 pilihan lokasi:

#### **PILIHAN A: Upload ke `/home/diskominfo/public_html/`** ⭐ RECOMMENDED

Jika Anda ingin Laravel dan frontend **di folder yang sama**.

**Struktur nanti:**
```
/home/diskominfo/public_html/
├── app/                    ← Laravel backend
├── routes/
├── vendor/
├── public/
│   └── uploads/
├── .env
└── ... (Laravel files)
```

**Document Root:** `/home/diskominfo/public_html/public`

**API endpoint:** `https://diskominfo.sanggau.go.id/api/banner`

---

#### **PILIHAN B: Upload ke `/home/diskominfo/laravel/`**

Jika Anda ingin Laravel backend **TERPISAH** dari frontend.

**Buat folder baru:**
1. Navigate ke `/home/diskominfo/`
2. Klik "+ Folder"
3. Nama: `laravel`
4. Create

**Struktur nanti:**
```
/home/diskominfo/
├── public_html/            ← Frontend Next.js
└── laravel/                ← Laravel backend
    ├── app/
    ├── routes/
    ├── vendor/
    └── public/
```

**Perlu setup Subdomain atau Proxy** untuk akses API.

---

### **STEP 3: Upload ZIP**

1. **Navigate ke folder tujuan** (`public_html/` atau `laravel/`)
2. **Klik "Upload"** di toolbar
3. **Select file:** `laravel-backend.zip` dari PC Anda
4. **Upload** → Tunggu hingga selesai
   - ⏳ **Estimasi:** 10-30 menit (tergantung ukuran & internet)
   - 💡 **Jangan close browser!**
5. **Setelah 100%** → Klik "Go Back to ..."

---

### **STEP 4: Extract ZIP**

1. **Kembali ke File Manager**
2. **Klik kanan `laravel-backend.zip`**
3. **Pilih "Extract"**
4. **Extract to:** (biarkan default, current folder)
5. **Klik "Extract File(s)"**
6. **Tungai hingga selesai** (5-10 menit)

---

### **STEP 5: Verify Extract Berhasil**

Setelah extract, di folder tujuan harus ada:
- ✅ Folder `app/`
- ✅ Folder `vendor/`
- ✅ Folder `public/`
- ✅ File `artisan`
- ✅ File `.env`
- ✅ File `composer.json`

---

### **STEP 6: Set Permissions**

**Folder `storage/` dan `bootstrap/cache/` harus writable:**

1. **Klik kanan folder `storage/`**
2. **"Change Permissions"**
3. **Set:** `0775` atau tick: Owner (RWX), Group (RWX), World (RX)
4. **Tick:** "Recurse into subdirectories"
5. **Apply**

Ulangi untuk folder:
- `bootstrap/cache/` → Permissions: `0775`
- `public/uploads/` → Permissions: `0775` (jika ada)

---

### **STEP 7: Delete ZIP (Cleanup)**

Setelah extract berhasil:
1. **Klik kanan `laravel-backend.zip`**
2. **Delete**

---

## 🧪 TESTING

### **TEST 1: Verify File artisan Exists**

1. File Manager → Navigate ke lokasi upload
2. **Cari file `artisan`**
3. ✅ **Harus ada!** File size ~1-2 KB

---

### **TEST 2: Test API Endpoint**

Buka di browser:

**Jika upload ke `/public_html/`:**
```
https://diskominfo.sanggau.go.id/api/banner
```

**Jika upload ke `/laravel/`:**
```
https://diskominfo.sanggau.go.id/laravel/public/api/banner
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

✅ **Jika muncul JSON** → SUCCESS!
❌ **Jika error Laravel muncul** → Cek .env database credentials
❌ **Jika masih 404** → Cek Document Root atau .htaccess

---

### **TEST 3: Test Homepage**

```
https://diskominfo.sanggau.go.id
```

- Jika upload ke `/public_html/` → Kemungkinan Laravel welcome page atau error
- Jika upload ke `/laravel/` → Frontend Next.js seharusnya tetap jalan

---

## 🚨 TROUBLESHOOTING

### **Problem: Upload ZIP gagal (file terlalu besar)**

**Solusi A: Upload via FTP**
1. Download FileZilla Client
2. Connect ke FTP server (credentials dari cPanel)
3. Upload `laravel-backend.zip`

**Solusi B: Compress vendor terpisah**
1. Compress tanpa folder `vendor/` → `laravel-no-vendor.zip`
2. Compress folder `vendor/` saja → `vendor.zip`
3. Upload 2 ZIP terpisah
4. Extract keduanya

---

### **Problem: Extract gagal (timeout)**

**Solusi:**
1. Compress dengan ukuran lebih kecil (split jadi beberapa ZIP)
2. Atau extract manual folder demi folder

---

### **Problem: API return error 500**

**Cek:**
1. `.env` database credentials benar?
2. Database exists di cPanel?
3. Tables sudah ada di database?
4. Permissions `storage/` sudah 0775?

**Temporary enable debug:**
```env
APP_DEBUG=true
```
Lalu akses API lagi, screenshot error nya.

---

### **Problem: API return 404 (setelah upload)**

**Cek:**
1. File `routes/api.php` exists?
2. `.htaccess` di folder `public/` exists?
3. Document Root sudah di-set ke folder `public/`?

---

## 📋 CHECKLIST UPLOAD

### Persiapan Local:
- [ ] ✅ Clear cache Laravel
- [ ] ✅ Generate vendor production
- [ ] ✅ Update .env credentials
- [ ] ✅ Compress project (exclude node_modules, .git, frontend)
- [ ] ✅ ZIP size reasonable (<500 MB)

### Upload cPanel:
- [ ] ✅ Login File Manager
- [ ] ✅ Navigate ke folder tujuan (`public_html/` or `laravel/`)
- [ ] ✅ Upload ZIP
- [ ] ✅ Extract ZIP
- [ ] ✅ Verify file `artisan` exists
- [ ] ✅ Set permissions storage & bootstrap/cache
- [ ] ✅ Delete ZIP (cleanup)

### Testing:
- [ ] ✅ File artisan exists di server
- [ ] ✅ API endpoint return JSON (tidak 404)
- [ ] ✅ Database connection OK
- [ ] ✅ Upload gambar di CMS berhasil
- [ ] ✅ Gambar muncul di frontend

---

## ⏱️ ESTIMASI WAKTU

```
Persiapan local:          10-15 menit
Compress ZIP:             5-15 menit
Upload ZIP:               10-30 menit (tergantung internet)
Extract ZIP:              5-10 menit
Set permissions:          2 menit
Testing:                  5 menit
────────────────────────────────────
TOTAL:                    37-77 menit (~1 jam)
```

---

## 🎉 SELESAI!

Setelah upload berhasil:
- ✅ Laravel backend sudah di server
- ✅ API sudah bisa diakses
- ✅ File Banner.php, Berita.php, dll sudah terupload (dalam ZIP)
- ✅ Gambar akan muncul dengan full URL
- ✅ Admin permissions sudah benar (karena routes/api.php sudah terupload)

**Next:** Test semua fitur di CMS!

---

**MULAI SEKARANG! 🚀**

1. Clear cache Laravel
2. Update .env
3. Compress project
4. Upload ZIP ke cPanel
5. Extract
6. Test API!

**Good Luck! 🎉**
