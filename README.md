# Website Kabupaten Sanggau - Backend

Backend API untuk website Diskominfo Kabupaten Sanggau.

---

## 🔧 Perbaikan Terbaru (v2.0.0)

### ✅ Masalah yang Diperbaiki:
1. **Gambar tidak muncul** di frontend setelah deploy production
2. **Role admin terbatas** - tidak bisa manage konten seperti banner, statistik, dll

### ✅ Solusi:
- Model auto-convert path relatif ke full URL di API response
- Routes API: admin bisa akses semua kecuali user management
- Upload gambar di CMS tetap normal (tidak ada perubahan)

**📖 Baca:** [FINAL-SUMMARY.md](./FINAL-SUMMARY.md) untuk detail lengkap

---

## 🚀 Quick Start

### Development (Localhost)

```bash
# Install dependencies
composer install

# Setup .env
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Run server
php artisan serve
# Access: http://localhost:8000
```

### Production (cPanel)

**📖 Baca:** [DEPLOY-READY.md](./DEPLOY-READY.md) untuk panduan lengkap

**Quick Commands:**
```bash
composer install --no-dev --optimize-autoloader
composer dump-autoload -o
php artisan migrate --force
php artisan config:cache
chmod -R 777 public/uploads
```

---

## 📁 Struktur Project

```
sanggau-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── Admin/          # Admin API endpoints
│   │   │   │   ├── AuthController  # Login/Logout
│   │   │   │   └── PublicController # Public API
│   │   │   └── Web/                # Web routes (optional)
│   │   └── Middleware/
│   │       └── CheckRole.php       # Role-based access control
│   ├── Models/
│   │   ├── Berita.php              # ✨ Updated: Full URL accessor
│   │   ├── Banner.php              # ✨ Updated: Full URL accessor
│   │   ├── Galeri.php              # ✨ Updated: Full URL accessor
│   │   └── ...
│   └── Helpers/
│       └── helpers.php             # ✨ New: Image URL helpers
├── routes/
│   ├── api.php                     # ✨ Updated: Admin permissions
│   └── web.php
├── config/
│   ├── cors.php                    # CORS configuration
│   └── filesystems.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── uploads/                    # 📁 Image storage
│   │   ├── berita/
│   │   ├── banner/
│   │   └── galeri/
│   └── index.php
├── .env.example
├── .env.production                 # ✨ New: Production template
└── composer.json                   # ✨ Updated: Autoload helpers
```

---

## 🔐 Authentication & Permissions

### Roles:
- **Superadmin:** Full access (termasuk user management)
- **Admin:** Manage semua konten (kecuali user management)
- **Penulis:** Hanya manage berita

### API Endpoints:

#### Public API (No Auth)
```
GET  /api/berita              # List berita
GET  /api/berita/{slug}       # Detail berita
GET  /api/banner              # List banner
GET  /api/galeri              # List galeri
GET  /api/agenda              # List agenda
GET  /api/pengumuman          # List pengumuman
GET  /api/statistik           # List statistik
GET  /api/layanan             # List layanan
```

#### Admin API (Auth Required)
```
# Berita (Admin, Penulis)
GET    /api/admin/berita
POST   /api/admin/berita
PUT    /api/admin/berita/{id}
DELETE /api/admin/berita/{id}

# Banner, Galeri, Video, dll (Admin only)
GET    /api/admin/banner
POST   /api/admin/banner
PUT    /api/admin/banner/{id}
DELETE /api/admin/banner/{id}

# User Management (Superadmin only)
GET    /api/admin/pengguna
POST   /api/admin/pengguna
PUT    /api/admin/pengguna/{id}
DELETE /api/admin/pengguna/{id}
```

**📖 Baca:** `routes/api.php` untuk daftar lengkap

---

## 🖼️ Image Handling

### Upload (di CMS):
```php
// Upload file gambar seperti biasa
$request->file('gambar')->move(public_path('uploads/berita'), $filename);

// Simpan relative path ke DB
$berita->gambar = '/uploads/berita/' . $filename;
```

### API Response:
```json
{
  "id": 1,
  "judul": "Berita Test",
  "gambar": "https://diskominfo.sanggau.go.id/uploads/berita/xxx.jpg"
}
```

Model otomatis convert path relatif ke full URL via accessor.

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### API Testing
```bash
# Test banner API
curl https://diskominfo.sanggau.go.id/api/banner

# Test with authentication
curl -H "Authorization: Bearer TOKEN" \
     https://diskominfo.sanggau.go.id/api/admin/berita
```

---

## 📚 Dokumentasi

- **[FINAL-SUMMARY.md](./FINAL-SUMMARY.md)** - Ringkasan perbaikan & cara deploy
- **[DEPLOY-READY.md](./DEPLOY-READY.md)** - Panduan deploy lengkap
- **[QUICK-REFERENCE.md](./QUICK-REFERENCE.md)** - Command quick reference
- **[FIX-SUMMARY.md](./FIX-SUMMARY.md)** - Technical details perbaikan
- **[DEPLOYMENT-GUIDE.md](./DEPLOYMENT-GUIDE.md)** - Backend deployment guide

---

## 🔧 Troubleshooting

### Gambar 404
```bash
chmod -R 777 public/uploads
```

### API Return Relative Path
```bash
php artisan config:clear
composer dump-autoload -o
```

### CORS Error
Edit `config/cors.php`, tambahkan domain frontend:
```php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://your-frontend.vercel.app',
],
```

**📖 Baca:** [DEPLOY-READY.md](./DEPLOY-READY.md#troubleshooting) untuk troubleshooting lengkap

---

## 🔄 Updates & Maintenance

### Update Dependencies
```bash
composer update
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Backup Database
```bash
php artisan backup:run
# atau
mysqldump -u root -p sanggau_db > backup.sql
```

---

## 🛡️ Security

### Production .env
```env
APP_ENV=production
APP_DEBUG=false          # MUST be false!
APP_KEY=your-app-key

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=sanggau_db
DB_USERNAME=user
DB_PASSWORD=secure-password

# URLs
APP_URL=https://diskominfo.sanggau.go.id
```

### File Permissions
```bash
# Storage & cache
chmod -R 755 storage bootstrap/cache

# Logs
chmod -R 777 storage/logs

# Uploads
chmod -R 777 public/uploads
```

---

## 📞 Support

**Issues?** 
1. Check [DEPLOY-READY.md](./DEPLOY-READY.md#troubleshooting)
2. Check [QUICK-REFERENCE.md](./QUICK-REFERENCE.md)
3. Check Laravel logs: `storage/logs/laravel.log`

---

## 📝 License

Proprietary - Pemerintah Kabupaten Sanggau

---

## 🎉 Changelog

### v2.0.0 (June 4, 2026)
- ✅ Fixed: Gambar tidak muncul di frontend
- ✅ Fixed: Admin role permissions
- ✅ Added: Model accessors untuk full URL
- ✅ Added: Helper functions
- ✅ Updated: Routes API permissions
- ✅ Added: Production deployment templates

### v1.0.0 (Initial Release)
- Initial backend API
- Authentication & authorization
- CRUD operations untuk semua resource

---

**Version:** 2.0.0  
**Status:** ✅ Production Ready  
**Last Updated:** June 4, 2026
