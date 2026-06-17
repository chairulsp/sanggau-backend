# 🚨 FIX API 404 - PANDUAN LENGKAP

## MASALAH: `https://diskominfo.sanggau.go.id/api/banner` Return 404

Berdasarkan analisis, ada **3 kemungkinan penyebab**:
1. Files belum diupload ke server
2. Document Root salah
3. .htaccess di lokasi yang salah

---

## 🔍 STEP 1: CEK STRUKTUR FOLDER DI cPanel

### A. Login cPanel File Manager

1. Login: `https://diskominfo.sanggau.go.id:2083/`
2. Klik **File Manager**
3. Navigate ke **public_html**

### B. Identifikasi Struktur

**Cek apakah ada folder `public/` di dalam `public_html/`:**

```
Struktur A (Laravel Standard):
/home/diskominfo/public_html/
├── app/
├── vendor/
├── routes/
├── public/         ← Folder ini ada?
│   ├── index.php
│   ├── .htaccess
│   └── uploads/
├── .env
└── composer.json
```

**ATAU:**

```
Struktur B (Document Root Langsung):
/home/diskominfo/public_html/
├── app/
├── vendor/
├── routes/
├── index.php       ← index.php langsung di public_html
├── .htaccess       ← .htaccess langsung di public_html
├── uploads/
├── .env
└── composer.json
```

**⚠️ PENTING: Catat struktur mana yang Anda lihat!**

---

## 🎯 STEP 2: FIX BERDASARKAN STRUKTUR

### JIKA STRUKTUR A (Ada folder `public/`)

#### 1. Set Document Root di cPanel

**⚠️ INI YANG PALING PENTING!**

1. **Exit File Manager**
2. **Kembali ke cPanel Home**
3. **Cari "Domains"** di cPanel
4. **Klik "Domains"** atau **"Addon Domains"**
5. **Cari domain:** `diskominfo.sanggau.go.id`
6. **Klik "Manage"** di sebelah kanan
7. **Lihat "Document Root":**
   - ❌ Jika: `/home/diskominfo/public_html`
   - ✅ Ubah ke: `/home/diskominfo/public_html/public`
8. **Save Changes**
9. **Tunggu 1-2 menit** (propagate)

#### 2. Verify .htaccess di folder `public/`

1. Buka `public_html/public/.htaccess`
2. Pastikan isinya seperti ini:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Pass Authorization header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Remove trailing slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Route all requests to index.php
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

3. **Save**

---

### JIKA STRUKTUR B (Tidak ada folder `public/`)

Berarti setup Anda **document root langsung ke public_html**.

#### 1. Pastikan .htaccess di `public_html/` benar

1. Buka `public_html/.htaccess`
2. Pastikan isinya:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Pass Authorization header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Remove trailing slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Route all requests to index.php
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

3. **HAPUS** rules tentang `www` dan proxy Next.js (itu buat setup lain)
4. **Save**

#### 2. Verify index.php ada di root

Pastikan `public_html/index.php` exists dan berisi:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Autoload
require __DIR__.'/vendor/autoload.php';

// Bootstrap
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

---

## 🧪 STEP 3: TEST API

Setelah fix Document Root atau .htaccess:

### Test 1: Homepage

```
https://diskominfo.sanggau.go.id
```

**Expected:** Homepage muncul (Next.js atau Laravel)

### Test 2: API Banner

```
https://diskominfo.sanggau.go.id/api/banner
```

**Expected Output:**
```json
[
  {
    "id": 1,
    "judul": "Test Banner",
    "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
  }
]
```

✅ **Jika muncul JSON** → SUCCESS!
❌ **Jika masih 404** → Lanjut ke STEP 4

---

## 🔧 STEP 4: TROUBLESHOOTING LANJUTAN

### Problem: Masih 404 setelah fix Document Root

#### A. Check routes/api.php exists

1. File Manager → `routes/api.php`
2. Pastikan ada route:
   ```php
   Route::get('/banner', [PublicController::class, 'banner']);
   ```

#### B. Check Controller exists

1. File Manager → `app/Http/Controllers/Api/PublicController.php`
2. Pastikan method `banner()` exists

#### C. Clear cache via File Manager

Jika tidak ada Terminal, hapus cache manual:

1. Delete folder: `bootstrap/cache/*.php` (kecuali `.gitignore`)
2. Delete folder: `storage/framework/cache/data/*`
3. Delete folder: `storage/framework/views/*`

#### D. Test direct file access

Buka di browser:
```
https://diskominfo.sanggau.go.id/index.php/api/banner
```

- ✅ Jika ini work → masalah di `.htaccess`
- ❌ Jika tetap 404 → masalah di Laravel routing

---

## 📋 CHECKLIST FIX API 404

### Struktur & Setup:
- [ ] ✅ Sudah cek struktur folder (ada `public/` atau tidak)
- [ ] ✅ Document Root sudah benar (`/public_html/public` atau `/public_html`)
- [ ] ✅ .htaccess ada di lokasi yang benar
- [ ] ✅ index.php ada di document root

### Files Updated (HARUS DIUPLOAD!):
- [ ] ✅ app/Models/Banner.php (fixed localhost)
- [ ] ✅ app/Models/Berita.php (fixed localhost)
- [ ] ✅ app/Models/Galeri.php (fixed localhost)
- [ ] ✅ app/Helpers/helpers.php (BUAT FOLDER!)
- [ ] ✅ routes/api.php (admin permissions)
- [ ] ✅ composer.json (autoload helpers)

### Testing:
- [ ] ✅ Homepage buka normal
- [ ] ✅ `/api/banner` return JSON
- [ ] ✅ JSON `gambar` field adalah full URL
- [ ] ✅ Upload banner baru → gambar muncul

---

## 🎯 KESIMPULAN

**Untuk setup Anda, folder Laravel ada di:**

```
/home/diskominfo/public_html/
```

**BUKAN** di `/home/diskominfo/laravel/` (itu setup berbeda).

**Document Root HARUS:**
- Jika ada folder `public/`: `/home/diskominfo/public_html/public`
- Jika tidak ada folder `public/`: `/home/diskominfo/public_html`

**Files yang WAJIB upload:**
1. 3 Models (Banner, Berita, Galeri)
2. helpers.php (buat folder Helpers dulu!)
3. routes/api.php
4. composer.json
5. Upload & extract vendor.zip (jika belum)

---

## ⚡ QUICK FIX CHECKLIST

**Jika API masih 404, coba ini urut:**

1. ✅ Set Document Root ke `/public_html/public` (jika ada folder public)
2. ✅ Verify .htaccess di folder yang benar
3. ✅ Upload 6 files yang sudah diupdate
4. ✅ Clear cache (delete files di `bootstrap/cache/` dan `storage/framework/`)
5. ✅ Test `https://diskominfo.sanggau.go.id/api/banner`
6. ✅ Test upload gambar di CMS

---

**Mulai dari STEP 1, catat struktur folder Anda, lalu ikuti panduan yang sesuai!**

**Good Luck! 🚀**
