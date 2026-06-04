# Konversi Full Laravel - Diskominfo Sanggau

## 📋 Deskripsi

Dokumen ini berisi panduan lengkap untuk konversi aplikasi web Diskominfo Sanggau dari arsitektur terpisah (Laravel Backend + Next.js Frontend) menjadi **Full Laravel dengan Blade Templates**.

## ✅ Yang Sudah Dibuat

### 1. Layout System

#### `resources/views/layouts/app.blade.php`
- **Master layout** dengan struktur HTML lengkap
- Integrasi Google Fonts (Plus Jakarta Sans)
- CSS Variables untuk theming (light/dark mode)
- Utility classes responsif
- Skeleton loader untuk UX yang lebih baik
- Scroll to top button
- Support untuk dark mode

#### `resources/views/layouts/navbar.blade.php`
- **Top bar** dengan informasi kontak dan tanggal
- **Navbar sticky** dengan efek blur saat scroll
- Logo dinamis dengan data dari database
- **Desktop menu** dengan dropdown support
- **Mobile responsive menu** (hamburger)
- Dark mode toggle button
- Auto-hide topbar saat scroll
- Integrasi dengan menu database
- Support untuk halaman dinamis (Laman)

#### `resources/views/layouts/footer.blade.php`
- **Ornamen khas Dayak-Melayu** Sanggau (SVG)
- 4 kolom informasi: Brand, Links, Layanan, Kontak
- Social media icons (Facebook, Instagram, YouTube)
- Informasi kontak dinamis dari database
- Responsive grid layout

### 2. Homepage

#### `resources/views/web/home.blade.php`
- ✅ **Hero Slider** dengan auto-slide (5.5 detik)
- ✅ **Section Layanan Digital** (grid 4 kolom)
- ✅ **Section Berita Terbaru** (grid 3 kolom)
- ✅ **Section Statistik** dengan background gradient
- ✅ **Section Pengumuman & Agenda** (2 kolom)
- ✅ Full responsive
- ✅ Animasi dan transisi smooth
- ✅ Data dari database (Cache: 5-10 menit)

### 3. Halaman Berita

#### `resources/views/web/berita/index.blade.php`
- ✅ Page hero dengan breadcrumb
- ✅ Search bar realtime
- ✅ Category filters (pill buttons)
- ✅ News grid dengan card design modern
- ✅ Pagination support
- ✅ Empty state
- ✅ Hover effects yang smooth

### 4. Controllers

#### `app/Http/Controllers/Web/HomeController.php`
✅ Sudah ada dan lengkap dengan:
- Data caching untuk performance
- Banner, Pengumuman, Agenda
- Berita, Layanan, Statistik
- Profil Diskominfo & Pimpinan
- Coverage 4G, Settings

#### `app/Http/Controllers/Web/BeritaController.php`
✅ Sudah ada dengan:
- Index dengan pagination
- Detail dengan slug
- View counter
- Related news

## 🚧 Yang Perlu Dilengkapi

### 1. Halaman Publik Lainnya

Berikut halaman yang **perlu dibuat views-nya** (controllers sudah ada di `routes/web.php`):

#### `resources/views/web/berita/show.blade.php`
- Detail berita dengan layout 2 kolom
- Konten lengkap dengan gambar
- Meta info (penulis, tanggal, kategori, views)
- Share buttons
- Related news sidebar
- Breadcrumb navigation

#### `resources/views/web/galeri/index.blade.php`
- Tab switching: Foto vs Video
- Foto grid dengan lightbox
- Video grid dengan YouTube embed
- Empty state untuk masing-masing tab

#### `resources/views/web/pengumuman/index.blade.php`
- List pengumuman dengan badge "Penting"
- Filter berdasarkan status
- Tanggal mulai & selesai
- Pagination

#### `resources/views/web/agenda/index.blade.php`
- Calendar view atau list view
- Card dengan tanggal prominent
- Lokasi dan waktu
- Filter bulan/tahun

#### `resources/views/web/layanan/index.blade.php`
- Grid layanan dengan kategori
- Search dan filter
- Icon untuk masing-masing layanan
- Link eksternal

#### `resources/views/web/profil/index.blade.php`
- Profil Kepala Dinas dengan foto
- Sambutan singkat
- Visi & Misi Diskominfo
- Struktur Organisasi
- Tupoksi
- Sejarah singkat

#### `resources/views/web/ppid/index.blade.php`
- Informasi PPID
- Dokumen publik
- Prosedur permohonan informasi
- Kontak PPID

#### `resources/views/web/download/index.blade.php`
- List dokumen download
- Kategori dokumen
- File size dan format
- Download counter

#### `resources/views/web/kontak/index.blade.php`
- Form kontak/pengaduan
- Google Maps embed
- Informasi kontak lengkap
- Social media

#### `resources/views/web/laman/show.blade.php`
- Dynamic page dari database
- Rich text content
- Breadcrumb
- Meta SEO

### 2. Assets & Public Files

#### Perlu ditambahkan:
```
public/
├── css/
│   └── app.css (compiled)
├── js/
│   └── app.js (compiled)
├── images/
│   ├── logo-sanggau.png
│   └── placeholders/
```

#### Compile Assets dengan Laravel Mix:

**webpack.mix.js** (sudah ada):
```javascript
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .version();
```

Jalankan:
```bash
npm install
npm run dev    # Development
npm run prod   # Production
```

### 3. Environment Variables

Pastikan di `.env`:
```env
APP_NAME="Diskominfo Kabupaten Sanggau"
APP_URL=http://localhost:8000

# API External (optional)
KABAR_API_URL=https://kabarsanggau.com/api/berita

# Storage
FILESYSTEM_DISK=public
```

### 4. Storage Setup

```bash
php artisan storage:link
```

Pastikan folder `storage/app/public/` accessible via `public/storage/`

## 📝 Panduan Implementasi Lengkap

### Langkah 1: Setup Storage & Assets

```bash
# Link storage
php artisan storage:link

# Install npm dependencies
npm install

# Compile assets
npm run dev
```

### Langkah 2: Copy Logo

Copy logo sanggau ke:
```
public/images/logo-sanggau.png
```

Atau biarkan fallback ke Wikimedia (sudah di-handle di code).

### Langkah 3: Buat Views yang Tersisa

Gunakan struktur yang sama dengan `home.blade.php` dan `berita/index.blade.php`:

**Template dasar:**
```blade
@extends('layouts.app')

@section('title', 'Judul Halaman - Diskominfo Kabupaten Sanggau')

@push('styles')
<style>
    /* Custom styles untuk halaman ini */
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>/</span>
            <span>Halaman Saat Ini</span>
        </div>
        <h1>Judul Halaman</h1>
        <p>Deskripsi singkat</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- Content here -->
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Custom JS untuk halaman ini
</script>
@endpush
```

### Langkah 4: Testing

```bash
# Start Laravel server
php artisan serve

# Akses di browser
http://localhost:8000
```

**Checklist Testing:**
- [ ] Homepage loads dengan semua sections
- [ ] Navbar responsive (desktop & mobile)
- [ ] Dark mode toggle works
- [ ] Berita page dengan search & filter
- [ ] Footer dengan social media links
- [ ] Cache working (check query count)
- [ ] Images loading correctly

### Langkah 5: Optimization

#### Cache Views (Production)
```bash
php artisan view:cache
php artisan config:cache
php artisan route:cache
```

#### Image Optimization
```bash
composer require intervention/image
```

Gunakan untuk resize/optimize gambar saat upload.

#### Enable OPcache (php.ini)
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

## 🎨 Design System

### Color Palette
```css
--primary: #1A56DB        /* Blue */
--primary-dark: #1D4ED8
--secondary: #F59E0B      /* Amber */
--text-primary: #0F172A   /* Slate 900 */
--text-secondary: #374151 /* Gray 700 */
--text-muted: #64748B     /* Slate 500 */
--bg-surface: #FFFFFF
--border: #E2E8F0         /* Slate 200 */
```

### Typography
- **Font**: Plus Jakarta Sans
- **Weights**: 400, 500, 600, 700, 800, 900
- **Headings**: 900 weight
- **Body**: 400-600 weight

### Spacing Scale
```css
.section { padding: 5rem 0; }      /* 80px */
.container { max-width: 1280px; }
Grid gaps: 0.75rem, 1rem, 1.25rem, 1.5rem
```

### Border Radius
```css
Cards: 16px - 20px
Buttons: 8px - 10px
Pills: 999px (fully rounded)
```

### Shadows
```css
/* Card hover */
box-shadow: 0 12px 32px rgba(0,0,0,0.08);

/* Button hover */
box-shadow: 0 8px 24px rgba(26,86,219,0.35);
```

## 📱 Responsive Breakpoints

```css
/* Tablet */
@media (max-width: 1024px) { }

/* Mobile */
@media (max-width: 768px) {
    .section { padding: 3rem 0; }
    .grid-3, .grid-4 { grid-template-columns: 1fr; }
}

/* Small mobile */
@media (max-width: 480px) { }
```

## 🔧 Fitur Tambahan yang Bisa Diimplementasikan

### 1. PWA Support
- Service Worker
- Manifest.json
- Offline page
- Install prompt

### 2. SEO Optimization
- Open Graph tags
- Twitter Cards
- Structured data (JSON-LD)
- Sitemap.xml
- Robots.txt

### 3. Performance
- Lazy loading images
- Code splitting
- CDN untuk assets
- Minify HTML/CSS/JS

### 4. Accessibility
- ARIA labels
- Keyboard navigation
- Screen reader support
- Focus indicators

### 5. Analytics
- Google Analytics 4
- Visitor tracking (sudah ada model)
- Heatmap (Hotjar/Microsoft Clarity)

## 🐛 Troubleshooting

### Issue: Images tidak muncul
**Solution:**
```bash
php artisan storage:link
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Issue: CSS/JS tidak ter-update
**Solution:**
```bash
npm run dev
php artisan view:clear
php artisan cache:clear
```

### Issue: Error 500 saat akses halaman
**Solution:**
```bash
php artisan config:clear
php artisan route:clear
chmod -R 775 storage
```

### Issue: Menu tidak muncul
**Solution:**
Pastikan ada data di tabel `menus` dan kolom `aktif = true`

## 📚 Referensi

- Laravel Blade Docs: https://laravel.com/docs/blade
- Tailwind CSS (optional): https://tailwindcss.com
- Laravel Mix: https://laravel-mix.com
- Intervention Image: http://image.intervention.io

## 👨‍💻 Maintenance

### Regular Tasks
1. **Update Dependencies**
   ```bash
   composer update
   npm update
   ```

2. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

3. **Backup Database**
   ```bash
   php artisan backup:run
   ```

4. **Monitor Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## ✨ Kesimpulan

Struktur dasar full Laravel sudah dibuat dengan:
- ✅ Layout system lengkap (navbar, footer)
- ✅ Homepage dengan 5 sections
- ✅ Halaman Berita dengan search & filter
- ✅ Responsive design
- ✅ Dark mode support
- ✅ Performance optimization (caching)

**Next Steps:**
1. Buat views untuk halaman publik lainnya (menggunakan template yang sudah ada)
2. Setup assets compilation (npm run dev)
3. Testing semua halaman
4. Deploy to production

**Catatan:** Semua tampilan sudah dirancang sama persis dengan frontend Next.js sebelumnya, hanya teknologinya yang diubah ke Blade Templates.
