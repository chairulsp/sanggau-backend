# 🎯 CARA SET DOCUMENT ROOT DI cPanel - FIX API 404

## ⚠️ INI PENYEBAB UTAMA API 404!

Berdasarkan `.htaccess` Anda yang sudah benar, masalahnya adalah **Document Root belum di-set ke folder `public/`**.

---

## 📋 LANGKAH-LANGKAH

### **STEP 1: Login cPanel**

```
URL: https://diskominfo.sanggau.go.id:2083/
atau: https://sanggau.go.id:2083/

Username: (username cPanel Anda)
Password: (password cPanel Anda)
```

---

### **STEP 2: Cari Menu "Domains"**

Di cPanel Homepage, cari salah satu dari menu ini:

- **"Domains"** (cPanel theme Jupiter/Paper Lantern)
- **"Addon Domains"** (cPanel theme lama)
- **"Zone Editor"** (kadang ada di sini juga)

**Lokasi biasanya:** Di section **"Domains"** atau kategori **"DOMAINS"**

![Contoh lokasi menu Domains di cPanel]

---

### **STEP 3: Manage Domain**

1. **Cari domain:** `diskominfo.sanggau.go.id`
2. **Klik tombol "Manage"** di sebelah kanan domain tersebut

Alternatif (jika tidak ada tombol Manage):
- Klik icon **"Gear"** ⚙️ atau **"Pencil"** ✏️ di sebelah domain

---

### **STEP 4: Edit Document Root**

1. **Lihat field "Document Root"**
   
   Kemungkinan saat ini berisi:
   ```
   /home/diskominfo/public_html
   ```
   atau
   ```
   public_html
   ```

2. **Ubah ke:**
   ```
   /home/diskominfo/public_html/public
   ```
   atau (jika path relatif)
   ```
   public_html/public
   ```

3. **Save Changes** / **Update Domain**

---

### **STEP 5: Tunggu Propagasi**

Setelah save, tunggu **1-2 menit** agar perubahan aktif.

---

### **STEP 6: Test API**

Buka di browser (force refresh dengan Ctrl+Shift+R):

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

✅ **Jika muncul JSON** → SUCCESS! Lanjut upload files yang sudah difix!
❌ **Jika masih 404** → Lanjut ke troubleshooting

---

## 🔧 TROUBLESHOOTING

### **Problem: Tidak ada menu "Domains" di cPanel**

**Solusi A:** Cari di **"Addon Domains"**
- Di Addon Domains, ada list domain
- Klik "Modify" atau icon edit di sebelah domain

**Solusi B:** Hubungi hosting provider
- Mungkin akun Anda tidak punya akses ke Domains management
- Provider harus set Document Root dari sisi mereka

**Solusi C:** Gunakan `.htaccess` redirect (workaround)

Jika benar-benar tidak bisa set Document Root, buat `.htaccess` di `/home/diskominfo/public_html/` (root) dengan isi:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect semua request ke folder public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**⚠️ Tapi ini TIDAK disarankan!** Lebih baik set Document Root yang benar.

---

### **Problem: Masih 404 setelah set Document Root**

#### Check 1: Verify Document Root sudah update

1. Kembali ke Domains → Manage
2. Lihat Document Root sekarang apa
3. Harus: `/home/diskominfo/public_html/public`

#### Check 2: Test direct PHP access

Buka di browser:
```
https://diskominfo.sanggau.go.id/index.php/api/banner
```

- ✅ Jika ini work → `.htaccess` tidak jalan, cek mod_rewrite enabled
- ❌ Jika tetap 404 → masalah di Laravel routes

#### Check 3: Verify index.php exists

File Manager → `public_html/public/index.php` harus ada.

Isi file harus mirip ini:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

**⚠️ Path `/../vendor/autoload.php` dan `/../bootstrap/app.php` harus ada `..` karena naik 1 level dari folder `public/`**

---

### **Problem: Homepage juga jadi error setelah ganti Document Root**

Ini normal jika homepage sebelumnya di-serve dari `/public_html/` (bukan dari Laravel).

**Solusi:**
- Jika homepage pakai Next.js/frontend terpisah, setup proxy atau subdomain
- Jika homepage dari Laravel, seharusnya malah jadi bener sekarang

---

## 📋 CHECKLIST SET DOCUMENT ROOT

- [ ] ✅ Login cPanel
- [ ] ✅ Buka menu Domains
- [ ] ✅ Klik Manage di domain `diskominfo.sanggau.go.id`
- [ ] ✅ Edit Document Root → tambahkan `/public` di akhir
- [ ] ✅ Save Changes
- [ ] ✅ Tunggu 1-2 menit
- [ ] ✅ Test: `https://diskominfo.sanggau.go.id/api/banner`
- [ ] ✅ Verify return JSON (bukan 404)

---

## 🎉 SETELAH API JALAN

**Jika API sudah return JSON (bukan 404), LANGKAH SELANJUTNYA:**

### **1. Upload Files yang Sudah Difix**

Upload files ini ke `/home/diskominfo/public_html/`:

```
✅ app/Models/Banner.php       (fix localhost fallback)
✅ app/Models/Berita.php       (fix localhost fallback)
✅ app/Models/Galeri.php       (fix localhost fallback)
✅ app/Helpers/helpers.php     (BUAT FOLDER app/Helpers/ dulu!)
✅ routes/api.php              (admin permissions)
✅ composer.json               (autoload helpers)
```

### **2. Upload & Extract vendor.zip (jika belum)**

Jika `vendor/` di server belum update atau belum ada autoload untuk Helpers.

### **3. Edit .env**

Pastikan:
```env
APP_URL=https://diskominfo.sanggau.go.id
APP_ENV=production
APP_DEBUG=false

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=diskominfo_sanggaudb
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### **4. Test Upload Gambar**

1. Login CMS sebagai admin
2. Upload banner baru dengan gambar
3. Refresh homepage
4. Verify gambar muncul

---

## ⏱️ ESTIMASI WAKTU

```
Set Document Root:       5 menit
Tunggu propagasi:        1-2 menit
Test API:                1 menit
Upload files (6):        10 menit
Upload vendor (optional): 20-30 menit
Edit .env:               2 menit
Test upload gambar:      5 menit
─────────────────────────────────
TOTAL:                   25-55 menit
```

---

## 🚀 MULAI SEKARANG!

**Priority #1:** Set Document Root ke `/public_html/public`

Ini yang **PALING PENTING** dan akan fix API 404!

Setelah API jalan, baru upload files yang sudah difix agar gambar muncul dengan full URL.

**Good Luck! 🎉**
