# 🔧 Perbaikan Backend & Frontend - Sanggau Website

## 📌 Overview

Repositori ini berisi perbaikan untuk 2 masalah utama:
1. **Gambar tidak muncul** di frontend setelah deploy ke production
2. **Role admin terbatas** - tidak bisa manage konten seperti superadmin

---

## ✅ Status Perbaikan

### Backend
- ✅ Model accessor untuk auto-convert path relatif ke full URL
- ✅ Helper functions `full_asset_url()` dan `format_image_url()`
- ✅ Routes API - pindahkan resource ke `role:admin`
- ✅ .env production template
- ✅ Dokumentasi deployment lengkap

### Frontend  
- ✅ Helper `resolveImageUrl()` untuk backward compatibility
- ✅ Update semua komponen image handling
- ✅ Dokumentasi deployment

---

## 🚀 Quick Start

### Development (Localhost)

```bash
# Backend
cd sanggau-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

# Frontend
cd sanggau-frontend
npm install
npm run dev
```

### Production Deploy

#### Backend (cPanel)

```bash
# 1. Upload files via FTP/File Manager
# 2. SSH ke server
ssh user@diskominfo.sanggau.go.id

# 3. Setup
cd public_html
composer install --no-dev --optimize-autoloader
composer dump-autoload -o
cp .env.production .env
nano .env  # Edit DB credentials

# 4. Cache & Permissions
php artisan migrate --force
php artisan config:cache
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs public/uploads

# 5. Test
curl https://diskominfo.sanggau.go.id/api/banner
```

#### Frontend (Vercel)

```bash
git add .
git commit -m "fix: Image handling & admin permissions"
git push origin main
# Vercel auto-deploy
```

---

## 📚 Dokumentasi Lengkap

### Backend
- **[FIX-SUMMARY.md](./FIX-SUMMARY.md)** - Ringkasan lengkap semua perbaikan
- **[DEPLOYMENT-GUIDE.md](./DEPLOYMENT-GUIDE.md)** - Panduan deploy ke cPanel
- **[ADMIN-CMS-GUIDE.md](./ADMIN-CMS-GUIDE.md)** - Panduan CMS Admin

### Frontend
- **[DEPLOYMENT-UPDATE.md](./sanggau-frontend/DEPLOYMENT-UPDATE.md)** - Update frontend & deploy

---

## 🔑 Role Permissions

| Feature | Superadmin | Admin | Penulis |
|---------|:----------:|:-----:|:-------:|
| Berita | ✅ | ✅ | ✅ |
| Banner | ✅ | ✅ | ❌ |
| Galeri | ✅ | ✅ | ❌ |
| Video | ✅ | ✅ | ❌ |
| Agenda | ✅ | ✅ | ❌ |
| Pengumuman | ✅ | ✅ | ❌ |
| Layanan | ✅ | ✅ | ❌ |
| Statistik | ✅ | ✅ | ❌ |
| Menu | ✅ | ✅ | ❌ |
| Pegawai | ✅ | ✅ | ❌ |
| Settings | ✅ | ✅ | ❌ |
| **Manajemen User** | ✅ | ❌ | ❌ |

---

## 🧪 Testing

### Backend API Test

```bash
# Test banner dengan full URL
curl https://diskominfo.sanggau.go.id/api/banner | jq '.[0].gambar'
# Expected: "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"

# Test berita
curl https://diskominfo.sanggau.go.id/api/berita | jq '.[0].gambar'

# Test galeri
curl https://diskominfo.sanggau.go.id/api/galeri | jq '.[0].gambar'

# Test admin access (need token)
curl -H "Authorization: Bearer TOKEN" \
  https://diskominfo.sanggau.go.id/api/admin/banner

# Test admin user management (should 403 for admin, 200 for superadmin)
curl -H "Authorization: Bearer TOKEN" \
  https://diskominfo.sanggau.go.id/api/admin/pengguna
```

### Frontend Test

1. Open https://your-frontend.vercel.app
2. Check homepage banner images load
3. Check berita page images load
4. Check galeri page images load
5. Upload new berita with image in CMS
6. Verify image appears in frontend

---

## 🐛 Troubleshooting

### Gambar 404

```bash
# Check file exists
ls -la public/uploads/berita/

# Check permissions
chmod -R 777 public/uploads

# Check .htaccess
cat public/uploads/.htaccess  # Should not have "Deny from all"

# Test direct access
curl -I https://diskominfo.sanggau.go.id/uploads/berita/test.jpg
```

### Gambar Masih Relative Path

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
composer dump-autoload -o

# Check APP_URL
cat .env | grep APP_URL
# Should be: APP_URL=https://diskominfo.sanggau.go.id
```

### Admin 403 Forbidden

```bash
# Check routes
php artisan route:list | grep admin/banner
# Should show: role:admin

# Check user role
mysql -u root -p
SELECT id, name, role FROM users;

# Clear route cache
php artisan route:clear
```

### CORS Error

Edit `config/cors.php`:
```php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://your-frontend.vercel.app',
],
```

Then:
```bash
php artisan config:clear
php artisan config:cache
```

---

## 📞 Support

Dokumentasi lengkap tersedia di:
- [FIX-SUMMARY.md](./FIX-SUMMARY.md) - Overview perbaikan
- [DEPLOYMENT-GUIDE.md](./DEPLOYMENT-GUIDE.md) - Deploy backend
- [DEPLOYMENT-UPDATE.md](./sanggau-frontend/DEPLOYMENT-UPDATE.md) - Deploy frontend

---

## 🎉 Changes Summary

### Backend
- ✅ 3 Models updated (Berita, Banner, Galeri) dengan accessor & mutator
- ✅ 1 Helper file baru `app/Helpers/helpers.php`
- ✅ Routes API - semua resource accessible oleh admin, kecuali user management
- ✅ `.env.production` template untuk production
- ✅ 3 Dokumentasi files (FIX-SUMMARY, DEPLOYMENT-GUIDE, README-FIXES)

### Frontend
- ✅ `lib/api.ts` - Tambah `resolveImageUrl()` helper
- ✅ 3 Pages updated (homepage, berita, galeri)
- ✅ 1 Dokumentasi (DEPLOYMENT-UPDATE)
- ✅ Backward compatible dengan backend lama

---

**Last Updated:** June 4, 2026
**Version:** 2.0.0
**Status:** ✅ Ready for Production
