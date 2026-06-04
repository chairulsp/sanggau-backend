# ⚡ Quick Start Guide - Full Laravel Diskominfo Sanggau

Panduan cepat untuk menjalankan aplikasi web Diskominfo Sanggau versi Full Laravel.

## 🎯 Yang Sudah Dibuat

### ✅ Layouts & Components
1. **Master Layout** - `resources/views/layouts/app.blade.php`
2. **Navbar** - `resources/views/layouts/navbar.blade.php` (responsive, dark mode)
3. **Footer** - `resources/views/layouts/footer.blade.php` (ornamen Dayak-Melayu)

### ✅ Halaman Publik (Views)
1. **Homepage** - `resources/views/web/home.blade.php`
2. **Berita Index** - `resources/views/web/berita/index.blade.php`
3. **Detail Berita** - `resources/views/web/berita/show.blade.php`
4. **Galeri** - `resources/views/web/galeri/index.blade.php`

### ✅ Controllers (Sudah Ada)
- `HomeController`, `BeritaController`, `GaleriController` dll sudah lengkap
- Routes sudah configured di `routes/web.php`

## 🚀 Langkah 1: Setup Basic (5 menit)

```bash
# Pastikan sudah di direktori project
cd c:\xampp\htdocs\sanggau-backend

# Install dependencies PHP (skip jika sudah)
composer install

# Install dependencies Node (skip jika sudah)
npm install

# Copy logo (jika ada)
# Copy file logo-sanggau.png ke public/images/
# Atau biarkan fallback ke Wikimedia

# Link storage
php artisan storage:link

# Compile assets
npm run dev
```

## 🎨 Langkah 2: Test Homepage (2 menit)

```bash
# Start Laravel server
php artisan serve
```

Buka browser: `http://localhost:8000`

**Checklist:**
- ✅ Homepage loads dengan hero, layanan, berita
- ✅ Navbar responsive (coba resize browser)
- ✅ Dark mode toggle works
- ✅ Footer dengan ornamen muncul
- ✅ Scroll to top button appears saat scroll

## 📰 Langkah 3: Test Berita (2 menit)

Klik menu **Berita** atau akses: `http://localhost:8000/berita`

**Checklist:**
- ✅ Berita grid muncul
- ✅ Search bar works (ketik untuk filter)
- ✅ Category filter works (klik kategori)
- ✅ Klik satu berita → masuk detail page
- ✅ Share buttons di detail page works

## 🖼️ Langkah 4: Test Galeri (2 menit)

Klik menu **Galeri** atau akses: `http://localhost:8000/galeri`

**Checklist:**
- ✅ Tab Foto & Video works
- ✅ Klik foto → lightbox opens
- ✅ Klik video → YouTube modal opens
- ✅ Close button works (X atau Escape)

## 📱 Langkah 5: Test Responsive (3 menit)

1. Buka DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Test di beberapa ukuran:
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1280px)

**Checklist:**
- ✅ Hamburger menu di mobile
- ✅ Grid berubah responsif
- ✅ Images tidak overflow
- ✅ Text readable di semua ukuran

## ⚙️ Langkah 6: Troubleshooting (jika ada masalah)

### Problem: CSS tidak muncul
```bash
npm run dev
php artisan view:clear
# Refresh browser (Ctrl+Shift+R)
```

### Problem: Images tidak muncul
```bash
php artisan storage:link
chmod -R 775 storage (Linux/Mac)
```

### Problem: Error 500
```bash
php artisan config:clear
php artisan cache:clear
chmod -R 775 storage bootstrap/cache
```

### Problem: Menu tidak muncul
Pastikan ada data di database tabel `menus` dengan `aktif = true`

## 🎯 Langkah 7: Membuat Halaman Baru (10 menit/page)

Mari buat halaman **Pengumuman** sebagai contoh:

### 1. Buat View File

```bash
# Buat folder jika belum ada
mkdir resources/views/web/pengumuman

# Buat file index.blade.php
# Copy template dari berita/index.blade.php dan sesuaikan
```

### 2. Template Dasar

```blade
@extends('layouts.app')

@section('title', 'Pengumuman - Diskominfo Kabupaten Sanggau')

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>/</span>
            <span>Pengumuman</span>
        </div>
        <h1>Pengumuman</h1>
        <p>Pengumuman resmi dari Diskominfo Kabupaten Sanggau</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- Content here -->
        @foreach($pengumuman as $p)
        <div>
            <h3>{{ $p->judul }}</h3>
            <p>{{ $p->isi }}</p>
        </div>
        @endforeach
    </div>
</section>
@endsection
```

### 3. Controller Sudah Ada

`PengumumanController` sudah ada di:
- `app/Http/Controllers/Web/PengumumanController.php`

### 4. Routes Sudah Ada

Check `routes/web.php`:
```php
Route::get('/pengumuman', [PengumumanController::class, 'index']);
```

### 5. Test

Akses: `http://localhost:8000/pengumuman`

## 📋 Halaman yang Masih Perlu Views

Gunakan template yang sama untuk membuat:

1. **Pengumuman** (`/pengumuman`)
   - List pengumuman dengan badge "Penting"
   - Filter berdasarkan tanggal
   - Card design seperti berita

2. **Agenda** (`/agenda`)
   - Calendar atau list view
   - Tanggal prominent
   - Lokasi dan waktu event

3. **Layanan** (`/layanan`)
   - Grid 4 kolom dengan icons
   - Search dan filter kategori
   - Link ke eksternal

4. **Profil** (`/profil`)
   - Profil Kepala Dinas
   - Visi & Misi
   - Struktur Organisasi
   - Tupoksi

5. **PPID** (`/ppid`)
   - Informasi PPID
   - Dokumen publik
   - Prosedur permohonan

6. **Download** (`/download`)
   - List dokumen dengan icon
   - File size info
   - Download counter

7. **Kontak** (`/kontak`)
   - Form kontak/pengaduan
   - Google Maps
   - Info kontak lengkap

8. **Laman** (`/laman/{slug}`)
   - Dynamic content dari DB
   - Rich text support

## 🎨 Design Guidelines

### Grid System
```html
<div class="grid-2">  <!-- 2 kolom -->
<div class="grid-3">  <!-- 3 kolom -->
<div class="grid-4">  <!-- 4 kolom -->
```

### Spacing
```html
<section class="section">  <!-- padding: 5rem 0 -->
<div class="container">    <!-- max-width: 1280px -->
```

### Cards
```html
<div style="background: var(--bg-surface); 
            border: 1px solid var(--border); 
            border-radius: 16px; 
            padding: 1.5rem;">
  <!-- Card content -->
</div>
```

### Buttons
```html
<a href="#" style="display: inline-flex; 
                   padding: 0.75rem 1.5rem; 
                   background: var(--primary); 
                   color: white; 
                   border-radius: 10px; 
                   font-weight: 700;">
  Button Text
</a>
```

## 🔄 Git Workflow (Recommended)

```bash
# Create feature branch
git checkout -b feature/add-pengumuman-page

# Make changes
# ... create views ...

# Stage changes
git add resources/views/web/pengumuman/

# Commit
git commit -m "feat: add pengumuman page with list and filters"

# Push
git push origin feature/add-pengumuman-page
```

## 🎯 Next Steps

1. ✅ Test 4 halaman yang sudah ada
2. 🔲 Buat halaman Pengumuman
3. 🔲 Buat halaman Agenda
4. 🔲 Buat halaman Layanan
5. 🔲 Buat halaman Profil
6. 🔲 Buat halaman PPID
7. 🔲 Buat halaman Download
8. 🔲 Buat halaman Kontak
9. 🔲 Buat halaman Laman dinamis
10. 🔲 Compile production assets (`npm run prod`)
11. 🔲 Deploy to production server

## 📊 Performance Checklist

### Development
- ✅ Cache config: NO (for development)
- ✅ Debug mode: YES
- ✅ Asset compilation: `npm run dev`

### Production
- ✅ Cache config: `php artisan config:cache`
- ✅ Cache routes: `php artisan route:cache`
- ✅ Cache views: `php artisan view:cache`
- ✅ Debug mode: NO (`APP_DEBUG=false`)
- ✅ Asset compilation: `npm run prod`
- ✅ Optimize autoloader: `composer dump-autoload --optimize`

## 💡 Tips & Tricks

### 1. Live Reload (Auto refresh saat edit)

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Watch assets
npm run watch
```

Browser akan auto-refresh saat edit CSS/JS!

### 2. Debug dengan dd()

```blade
<!-- Di view -->
{{ dd($variable) }}

<!-- Di controller -->
dd($data);
```

### 3. Cache Clear All

```bash
php artisan optimize:clear
```

Ini clear semua cache (config, routes, views, dll).

### 4. Query Debugging

Di controller:
```php
DB::enableQueryLog();
// ... your queries ...
dd(DB::getQueryLog());
```

### 5. Asset Versioning

Setelah compile production:
```blade
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<script src="{{ mix('js/app.js') }}"></script>
```

File akan auto-versioned untuk cache busting.

## 🎓 Learning Resources

- [Laravel Blade Docs](https://laravel.com/docs/8.x/blade)
- [Laravel Mix Docs](https://laravel-mix.com)
- [CSS Grid Guide](https://css-tricks.com/snippets/css/complete-guide-grid/)
- [Flexbox Guide](https://css-tricks.com/snippets/css/a-guide-to-flexbox/)

## ✅ Completion Checklist

**Current Status:**
- [x] Setup project
- [x] Layout system (navbar, footer)
- [x] Homepage
- [x] Berita (index + detail)
- [x] Galeri (foto + video)
- [ ] Pengumuman
- [ ] Agenda
- [ ] Layanan
- [ ] Profil
- [ ] PPID
- [ ] Download
- [ ] Kontak
- [ ] Laman
- [ ] Production deploy

## 🚀 Ready to Go!

Aplikasi dasar sudah jalan! Sekarang tinggal lengkapi halaman-halaman yang tersisa dengan mengikuti pola yang sama.

**Estimated time:** 1-2 jam untuk semua halaman yang tersisa.

---

**Need Help?**
- Check `KONVERSI-FULL-LARAVEL.md` untuk detail lengkap
- Check `README-LARAVEL-BLADE.md` untuk dokumentasi lengkap

**Happy Coding! 🎉**
