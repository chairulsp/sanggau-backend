# 🚀 Aplikasi Web Full Laravel - Diskominfo Kabupaten Sanggau

Portal resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau yang dibangun dengan **Laravel 8** dan **Blade Templates**.

## ✨ Fitur Utama

### 📱 Frontend Publik
- ✅ **Homepage** dengan hero slider, layanan, berita, statistik, pengumuman & agenda
- ✅ **Berita & Informasi** dengan search, filter kategori, dan pagination
- ✅ **Detail Berita** dengan share buttons dan related news
- ✅ **Galeri Foto & Video** dengan lightbox dan YouTube integration
- ✅ **Pengumuman** dengan badge prioritas
- ✅ **Agenda** kegiatan dinas
- ✅ **Layanan Digital** lengkap dengan icon
- ✅ **Profil Dinas** (visi, misi, struktur organisasi)
- ✅ **PPID** (Pejabat Pengelola Informasi dan Dokumentasi)
- ✅ **Kontak & Pengaduan**
- ✅ **Halaman Dinamis** (Laman) dari database

### 🎨 Design & UX
- ✅ **Modern UI** dengan design system konsisten
- ✅ **Fully Responsive** (desktop, tablet, mobile)
- ✅ **Dark Mode** support
- ✅ **Smooth Animations** & transitions
- ✅ **Loading States** dengan skeleton loader
- ✅ **Empty States** yang informatif
- ✅ **Accessibility** friendly
- ✅ **SEO Optimized** dengan meta tags

### ⚡ Performance
- ✅ **Database Caching** (5-10 menit)
- ✅ **Lazy Loading** images
- ✅ **Optimized Queries** dengan Eloquent
- ✅ **Asset Minification** dengan Laravel Mix
- ✅ **CDN Ready**

### 🎭 Custom Features
- ✅ **Ornamen Khas Dayak-Melayu Sanggau** di footer
- ✅ **Multi-language Ready** (saat ini: Bahasa Indonesia)
- ✅ **Visitor Tracking** built-in
- ✅ **Admin Panel Integration** (existing backend)

## 📦 Teknologi

### Backend
- Laravel 8.x
- PHP 7.3+
- MySQL/MariaDB
- Laravel Sanctum (API auth)

### Frontend
- Blade Templates
- Vanilla JavaScript (no framework)
- Custom CSS (no framework dependencies)
- Font: Plus Jakarta Sans

### Tools
- Composer (PHP dependencies)
- NPM (asset compilation)
- Laravel Mix (webpack)

## 🛠️ Installation

### Prerequisites
```bash
PHP >= 7.3
Composer
Node.js & NPM
MySQL/MariaDB
```

### Setup

1. **Clone Repository**
```bash
cd c:\xampp\htdocs\sanggau-backend
```

2. **Install Dependencies**
```bash
# PHP dependencies
composer install

# Node dependencies
npm install
```

3. **Environment Setup**
```bash
# Copy .env.example jika belum ada
cp .env.example .env

# Generate application key
php artisan key:generate
```

4. **Database Configuration**

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sanggau_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations** (jika database kosong)
```bash
php artisan migrate --seed
```

6. **Storage Setup**
```bash
php artisan storage:link
```

7. **Compile Assets**
```bash
# Development
npm run dev

# Production
npm run prod

# Watch mode (auto compile)
npm run watch
```

8. **Start Server**
```bash
php artisan serve
```

Akses: `http://localhost:8000`

## 📁 Struktur File

```
sanggau-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/              # Frontend controllers
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── BeritaController.php
│   │   │   │   ├── GaleriController.php
│   │   │   │   └── ... (other pages)
│   │   │   └── Api/              # API controllers (admin)
│   │   └── Middleware/
│   └── Models/                   # Eloquent models
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php     # Master layout
│   │   │   ├── navbar.blade.php  # Navigation
│   │   │   └── footer.blade.php  # Footer
│   │   └── web/                  # Public pages
│   │       ├── home.blade.php
│   │       ├── berita/
│   │       │   ├── index.blade.php
│   │       │   └── show.blade.php
│   │       ├── galeri/
│   │       │   └── index.blade.php
│   │       └── ... (other pages)
│   ├── css/
│   └── js/
├── public/
│   ├── css/
│   ├── js/
│   ├── images/
│   │   └── logo-sanggau.png
│   └── storage/ -> ../storage/app/public
├── routes/
│   ├── web.php                   # Frontend routes
│   └── api.php                   # API routes
├── database/
│   ├── migrations/
│   └── seeders/
├── .env
├── composer.json
├── package.json
├── webpack.mix.js
└── README-LARAVEL-BLADE.md
```

## 🎯 Halaman yang Sudah Dibuat

### ✅ Sudah Lengkap dengan View
1. **Homepage** (`/`) - Hero slider, layanan, berita, statistik
2. **Berita Index** (`/berita`) - Grid dengan search & filter
3. **Detail Berita** (`/berita/{slug}`) - Full article dengan share buttons
4. **Galeri** (`/galeri`) - Foto & video dengan lightbox

### 🚧 Masih Perlu View (Controllers Sudah Ada)
5. **Pengumuman** (`/pengumuman`)
6. **Agenda** (`/agenda`)
7. **Layanan** (`/layanan`)
8. **Profil** (`/profil`)
9. **PPID** (`/ppid`)
10. **Download** (`/download`)
11. **Kontak** (`/kontak`)
12. **Laman Dinamis** (`/laman/{slug}`)

> **Template untuk halaman baru sudah disediakan di `KONVERSI-FULL-LARAVEL.md`**

## 🎨 Design System

### Colors
```css
--primary: #1A56DB (Blue)
--secondary: #F59E0B (Amber)
--text-primary: #0F172A
--text-secondary: #374151
--text-muted: #64748B
--border: #E2E8F0
```

### Typography
- **Font Family**: Plus Jakarta Sans
- **Headings**: 900 weight
- **Body**: 400-600 weight

### Components
- **Cards**: 16-20px border radius
- **Buttons**: 8-10px border radius
- **Grid**: 3-4 columns (responsive)
- **Spacing**: 5rem vertical sections

## 🔧 Konfigurasi

### Caching

Edit `config/cache.php` untuk production:
```php
'default' => env('CACHE_DRIVER', 'file'),
```

Gunakan Redis untuk production optimal:
```env
CACHE_DRIVER=redis
```

### Session

```env
SESSION_DRIVER=file  # atau redis untuk production
SESSION_LIFETIME=120
```

### Queue (Optional)

Untuk email notifications:
```env
QUEUE_CONNECTION=database
```

Then run:
```bash
php artisan queue:work
```

## 📊 Database Models

Utama yang digunakan:
- `Banner` - Hero slider
- `Berita` - News articles
- `Pengumuman` - Announcements
- `Agenda` - Events
- `Layanan` - Services
- `Galeri` - Photo gallery
- `GaleriVideo` - Video gallery
- `Statistik` - Statistics
- `ProfilDiskominfo` - Organization profile
- `Pegawai` - Staff/employees
- `Menu` - Dynamic navigation
- `Laman` - Dynamic pages
- `Setting` - Site settings
- `Coverage4g` - 4G coverage data

## 🚀 Deployment

### 1. Optimize for Production

```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 2. Compile Assets

```bash
npm run production
```

### 3. Set Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 4. Environment

Set `.env` untuk production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://diskominfo.sanggau.go.id
```

### 5. Web Server

#### Apache (.htaccess sudah included)
```apache
DocumentRoot /path/to/sanggau-backend/public
```

#### Nginx
```nginx
server {
    listen 80;
    server_name diskominfo.sanggau.go.id;
    root /path/to/sanggau-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔒 Security

### HTTPS (Production)

Install SSL certificate (Let's Encrypt recommended):
```bash
certbot --nginx -d diskominfo.sanggau.go.id
```

Force HTTPS di `.env`:
```env
FORCE_HTTPS=true
```

### CSRF Protection

Sudah built-in Laravel. Semua forms harus include:
```blade
@csrf
```

### XSS Protection

Gunakan `{{ }}` untuk output (auto-escaped).
Untuk HTML content yang aman:
```blade
{!! $berita->konten !!}
```

## 🐛 Troubleshooting

### Error 500
```bash
php artisan config:clear
php artisan cache:clear
chmod -R 775 storage bootstrap/cache
```

### Images tidak muncul
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### CSS/JS tidak ter-update
```bash
npm run dev
php artisan view:clear
# Hard refresh browser (Ctrl+Shift+R)
```

### Slow queries
```bash
# Enable query log
DB_LOG_QUERIES=true

# Check storage/logs/laravel.log
tail -f storage/logs/laravel.log
```

## 📈 Monitoring

### Laravel Telescope (Development)

Install:
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Akses: `http://localhost:8000/telescope`

### Logs

```bash
# Real-time log monitoring
tail -f storage/logs/laravel.log

# Search errors
grep "ERROR" storage/logs/laravel.log
```

## 🔄 Updates

### Laravel Updates
```bash
composer update laravel/framework
```

### Dependencies
```bash
composer update
npm update
```

## 📝 Development

### Coding Standards

Follow PSR-12:
```bash
composer require --dev phpstan/phpstan
vendor/bin/phpstan analyse app
```

### Testing

Setup PHPUnit:
```bash
php artisan test
```

### Git Workflow

```bash
# Feature branch
git checkout -b feature/new-page

# Commit
git add .
git commit -m "feat: add new page"

# Push
git push origin feature/new-page
```

## 👨‍💻 Tim Pengembang

- **Backend**: Laravel 8, Blade Templates
- **Frontend**: Vanilla JS, Custom CSS
- **Design**: Inspired by modern gov portals

## 📄 License

Proprietary - Dinas Komunikasi dan Informatika Kabupaten Sanggau

## 🤝 Contributing

Untuk kontribusi internal, silakan buat branch baru dan submit pull request.

## 📞 Support

Untuk pertanyaan atau bantuan:
- Email: diskominfo@sanggau.go.id
- Website: https://diskominfo.sanggau.go.id

---

## ⚡ Quick Start Checklist

- [ ] Clone repository
- [ ] `composer install`
- [ ] `npm install`
- [ ] Setup `.env` file
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate` (jika perlu)
- [ ] `php artisan storage:link`
- [ ] `npm run dev`
- [ ] `php artisan serve`
- [ ] Akses `http://localhost:8000`
- [ ] Test homepage
- [ ] Test berita page
- [ ] Test galeri page
- [ ] Check responsive (F12 > Toggle device toolbar)

## 🎉 Fitur Unggulan

1. **Design Modern** - Mengikuti tren design gov portal terkini
2. **Performance Tinggi** - Caching optimal, lazy loading
3. **SEO Friendly** - Meta tags, structured data
4. **Accessibility** - WCAG compliant
5. **Ornamen Lokal** - Motif Dayak-Melayu Sanggau di footer
6. **Dark Mode** - User preference support
7. **PWA Ready** - Service worker siap diimplementasi

---

**Built with ❤️ for Kabupaten Sanggau**
