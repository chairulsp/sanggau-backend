# 📁 STRUKTUR FOLDER Laravel DI cPanel - PENJELASAN LENGKAP

## ❓ PERTANYAAN: Folder Laravel di mana?

**Ada 2 kemungkinan setup di cPanel:**

---

## 🏗️ SETUP A: LARAVEL STANDARD (Dengan folder `public/`)

### Struktur Folder:

```
/home/diskominfo/
└── public_html/                          ← Folder root Laravel
    ├── app/                              ← Application code
    │   ├── Http/
    │   │   └── Controllers/
    │   ├── Models/
    │   │   ├── Banner.php                ← UPDATE INI!
    │   │   ├── Berita.php                ← UPDATE INI!
    │   │   └── Galeri.php                ← UPDATE INI!
    │   └── Helpers/                      ← BUAT FOLDER INI!
    │       └── helpers.php               ← UPLOAD INI!
    ├── routes/
    │   └── api.php                       ← UPDATE INI!
    ├── vendor/                           ← Dependencies
    ├── public/                           ← ⭐ DOCUMENT ROOT HARUS KE SINI!
    │   ├── index.php                     ← Laravel entry point
    │   ├── .htaccess                     ← Routing rules
    │   └── uploads/                      ← Upload gambar
    │       ├── banner/
    │       ├── berita/
    │       └── galeri/
    ├── .env                              ← Config (APP_URL!)
    ├── composer.json                     ← UPDATE INI!
    └── ...
```

### Document Root Setting:

**✅ BENAR:**
```
Domain: diskominfo.sanggau.go.id
Document Root: /home/diskominfo/public_html/public
```

**❌ SALAH:**
```
Document Root: /home/diskominfo/public_html
```

### Cara Kerja:

1. User akses: `https://diskominfo.sanggau.go.id/api/banner`
2. Nginx/Apache serve dari: `/home/diskominfo/public_html/public/`
3. `.htaccess` di folder `public/` route request ke `index.php`
4. Laravel handle route `/api/banner`
5. PublicController@banner return JSON

### Files yang WAJIB Upload:

```
UPLOAD KE: /home/diskominfo/public_html/

app/Models/Banner.php
app/Models/Berita.php
app/Models/Galeri.php
app/Helpers/helpers.php        ← BUAT FOLDER app/Helpers/ DULU!
routes/api.php
composer.json
```

---

## 🏗️ SETUP B: DOCUMENT ROOT LANGSUNG (Tanpa folder `public/`)

### Struktur Folder:

```
/home/diskominfo/
└── public_html/                          ← Document Root LANGSUNG
    ├── app/                              ← Application code
    │   ├── Http/
    │   ├── Models/
    │   │   ├── Banner.php                ← UPDATE INI!
    │   │   ├── Berita.php                ← UPDATE INI!
    │   │   └── Galeri.php                ← UPDATE INI!
    │   └── Helpers/                      ← BUAT FOLDER INI!
    │       └── helpers.php               ← UPLOAD INI!
    ├── routes/
    │   └── api.php                       ← UPDATE INI!
    ├── vendor/                           ← Dependencies
    ├── index.php                         ← ⭐ LANGSUNG DI ROOT!
    ├── .htaccess                         ← ⭐ LANGSUNG DI ROOT!
    ├── uploads/                          ← Upload gambar
    │   ├── banner/
    │   ├── berita/
    │   └── galeri/
    ├── .env                              ← Config (APP_URL!)
    ├── composer.json                     ← UPDATE INI!
    └── ...
```

### Document Root Setting:

**✅ BENAR:**
```
Domain: diskominfo.sanggau.go.id
Document Root: /home/diskominfo/public_html
```

### Cara Kerja:

1. User akses: `https://diskominfo.sanggau.go.id/api/banner`
2. Nginx/Apache serve dari: `/home/diskominfo/public_html/`
3. `.htaccess` di root route request ke `index.php`
4. Laravel handle route `/api/banner`
5. PublicController@banner return JSON

### Files yang WAJIB Upload:

```
UPLOAD KE: /home/diskominfo/public_html/

app/Models/Banner.php
app/Models/Berita.php
app/Models/Galeri.php
app/Helpers/helpers.php        ← BUAT FOLDER app/Helpers/ DULU!
routes/api.php
composer.json
```

---

## 🔍 CARA CEK SETUP ANDA PAKAI MANA?

### Langkah:

1. **Login cPanel File Manager**
2. **Navigate ke:** `/home/diskominfo/public_html/`
3. **Lihat isi folder:**

#### JIKA ADA FOLDER `public/`:
```
public_html/
├── app/
├── vendor/
├── public/        ← INI ADA?
└── ...
```
→ **Anda pakai SETUP A**

#### JIKA `index.php` LANGSUNG DI ROOT:
```
public_html/
├── app/
├── vendor/
├── index.php      ← INI LANGSUNG DI ROOT?
└── ...
```
→ **Anda pakai SETUP B**

---

## ❌ SETUP C: LARAVEL TERPISAH (BUKAN UNTUK ANDA!)

**⚠️ INI BUKAN SETUP ANDA! Tapi untuk referensi:**

```
/home/diskominfo/
├── laravel/                              ← Laravel Backend TERPISAH
│   ├── app/
│   ├── public/
│   ├── vendor/
│   └── ...
└── public_html/                          ← Frontend/Proxy saja
    ├── .htaccess                         ← Redirect /api/* ke /laravel/public/
    └── index.html                        ← Frontend
```

**Ini setup terpisah dimana:**
- Frontend di `/public_html/`
- Backend di `/laravel/`
- `.htaccess` di `/public_html/` redirect API calls ke `/laravel/public/`

**⚠️ Berdasarkan .htaccess yang Anda tunjukkan, Anda TIDAK pakai setup ini!**

---

## 🎯 KESIMPULAN UNTUK ANDA

Berdasarkan .htaccess yang sudah Anda tunjukkan dan informasi yang ada:

### **Folder Laravel Anda ada di:**

```
/home/diskominfo/public_html/
```

**BUKAN** di `/home/diskominfo/laravel/` (setup terpisah).

### **Document Root harus:**

- **Jika ada folder `public/`**: `/home/diskominfo/public_html/public`
- **Jika tidak ada folder `public/`**: `/home/diskominfo/public_html`

### **Files yang harus diupload ke:**

```
/home/diskominfo/public_html/app/Models/Banner.php
/home/diskominfo/public_html/app/Models/Berita.php
/home/diskominfo/public_html/app/Models/Galeri.php
/home/diskominfo/public_html/app/Helpers/helpers.php
/home/diskominfo/public_html/routes/api.php
/home/diskominfo/public_html/composer.json
```

---

## 🚀 LANGKAH SELANJUTNYA

1. ✅ **Cek struktur folder** di cPanel (ada `public/` atau tidak)
2. ✅ **Set Document Root** sesuai struktur
3. ✅ **Upload 6 files** yang sudah diupdate
4. ✅ **Test API:** `https://diskominfo.sanggau.go.id/api/banner`
5. ✅ **Upload gambar** di CMS → verify muncul di frontend

---

## 📞 JIKA MASIH BINGUNG

**Kirim screenshot folder di cPanel:**
1. Login File Manager
2. Navigate ke `/home/diskominfo/public_html/`
3. Screenshot list files/folders
4. Screenshot Document Root setting di cPanel Domains

Dengan info itu, saya bisa pastikan 100% struktur mana yang Anda pakai.

---

**Semoga penjelasan ini membantu! 🚀**
