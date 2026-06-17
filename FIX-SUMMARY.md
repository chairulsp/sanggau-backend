# Summary Perbaikan Backend & Frontend

## 🔧 Masalah yang Diperbaiki

### 1. ❌ **Gambar Tidak Muncul di Frontend**

**Penyebab:**
- Backend menyimpan path relatif (`/uploads/berita/xxx.jpg`) di database
- Frontend mencoba akses gambar dengan prepend `BACKEND_ORIGIN`
- Tapi di production, path tidak accessible atau APP_URL salah
- Setelah deploy ke cPanel, gambar yang diupload tidak muncul di Vercel

**Solusi:**
✅ **Backend:** Model otomatis convert path relatif ke full URL via accessor
✅ **Frontend:** Helper function `resolveImageUrl()` untuk backward compatibility
✅ **Config:** Update APP_URL di `.env` ke production URL
✅ **Helper:** Function `full_asset_url()` dan `format_image_url()`

### 2. ❌ **Role Admin Terbatas**

**Penyebab:**
- Hampir semua resource management hanya bisa diakses `superadmin`
- Admin tidak bisa manage: banner, statistik, layanan, menu, video, laman, dokumen, ppid, dll
- Padahal harusnya admin bisa manage konten, hanya user management yang khusus superadmin

**Solusi:**
✅ Pindahkan hampir semua resource ke `role:admin` middleware
✅ **HANYA** manajemen pengguna (`/admin/pengguna`) yang khusus `role:superadmin`
✅ Admin bisa lihat visitor stats & login history (read-only)
✅ Hanya superadmin bisa delete login history

---

## 📋 Perubahan File

### Backend Changes

#### 1. **New Files**
- ✅ `app/Helpers/helpers.php` - Helper functions untuk full URL
- ✅ `.env.production` - Template .env untuk production
- ✅ `DEPLOYMENT-GUIDE.md` - Panduan deployment lengkap

#### 2. **Modified Files**

**composer.json**
```json
"autoload": {
    "files": ["app/Helpers/helpers.php"]
}
```

**routes/api.php**
- Pindahkan resource dari `role:superadmin` ke `role:admin`
- Hanya `pengguna` dan `delete login-history` yang masih `role:superadmin`

**app/Models/Berita.php, Galeri.php, Banner.php**
```php
public function getGambarAttribute($value) {
    if (empty($value)) return null;
    if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
        return $value;
    }
    return full_asset_url($value);
}
```

**.env**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://diskominfo.sanggau.go.id
```

### Frontend Changes

#### 1. **Modified Files**

**src/lib/api.ts**
```typescript
export function resolveImageUrl(imagePath: string | undefined | null): string {
  if (!imagePath) return '';
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  return `${BACKEND_ORIGIN}${imagePath.startsWith('/') ? '' : '/'}${imagePath}`;
}
```

**src/app/(public)/page.tsx**
```typescript
import { resolveImageUrl } from "@/lib/api";
const imgSrc = (src: string | undefined) => resolveImageUrl(src || '');
```

**src/app/(public)/berita/page.tsx**
```typescript
const resolveImg = (src?: string) => resolveImageUrl(src || '');
```

**src/app/(public)/galeri/page.tsx**
```typescript
const resolveImg = (src: string) => resolveImageUrl(src);
```

---

## 🚀 Cara Deploy

### Backend ke cPanel

```bash
# 1. Upload ke cPanel via FTP atau File Manager
# 2. SSH ke server
ssh username@diskominfo.sanggau.go.id

# 3. Install dependencies
cd public_html
composer install --optimize-autoloader --no-dev
composer dump-autoload -o

# 4. Setup .env
cp .env.production .env
nano .env  # Edit DB credentials

# 5. Run migrations & cache
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# 6. Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs public/uploads

# 7. Verify
curl https://diskominfo.sanggau.go.id/api/banner
# Response harus punya full URL di field "gambar"
```

### Frontend ke Vercel

```bash
# 1. Push changes
git add .
git commit -m "fix: Update image handling & admin role permissions"
git push origin main

# 2. Vercel auto deploy
# Atau manual: vercel --prod

# 3. Verify environment variables di Vercel Dashboard:
NEXT_PUBLIC_API_URL=https://diskominfo.sanggau.go.id/api
```

---

## ✅ Testing Checklist

### Backend Testing

- [ ] API `/api/banner` return full URL untuk gambar
- [ ] API `/api/berita` return full URL untuk gambar
- [ ] API `/api/galeri` return full URL untuk gambar
- [ ] Upload gambar baru via CMS
- [ ] Gambar accessible via direct URL
- [ ] Login sebagai **admin**:
  - [ ] Bisa akses `/admin/banner`
  - [ ] Bisa akses `/admin/statistik`
  - [ ] Bisa akses `/admin/layanan`
  - [ ] Bisa akses `/admin/galeri`
  - [ ] Bisa akses `/admin/video`
  - [ ] Bisa akses `/admin/menu`
  - [ ] Bisa akses `/admin/laman`
  - [ ] Bisa lihat `/admin/visitor-stats`
  - [ ] Bisa lihat `/admin/login-history`
  - [ ] **TIDAK** bisa akses `/admin/pengguna` (403)
- [ ] Login sebagai **superadmin**:
  - [ ] Bisa akses semua endpoint
  - [ ] Bisa akses `/admin/pengguna`
  - [ ] Bisa delete login history

### Frontend Testing

- [ ] Homepage banner muncul
- [ ] Berita list gambar muncul
- [ ] Galeri foto muncul
- [ ] Upload berita baru di CMS
- [ ] Gambar berita baru langsung muncul di frontend
- [ ] Tidak ada CORS error di console
- [ ] Tidak ada 404 error untuk gambar
- [ ] Gambar load dengan cepat (cache working)

### Integration Testing

- [ ] Admin login ke CMS → Upload banner baru → Banner muncul di homepage
- [ ] Admin login ke CMS → Upload berita dengan gambar → Berita & gambar muncul di /berita
- [ ] Admin login ke CMS → Upload foto galeri → Foto muncul di /galeri
- [ ] Admin edit berita existing → Update gambar → Gambar update muncul
- [ ] Test dari berbagai device (mobile, tablet, desktop)
- [ ] Test dari berbagai browser (Chrome, Firefox, Safari, Edge)

---

## 🎯 Role Permissions Summary

### Superadmin (Full Access)
✅ Semua konten management (berita, banner, galeri, video, agenda, pengumuman)
✅ Semua data management (layanan, statistik, skpd, menu, dokumen, ppid)
✅ Pegawai management
✅ Settings & profil diskominfo
✅ **User management** (create, edit, delete users)
✅ **Delete login history**
✅ Coverage 4G & pengaduan
✅ Visitor stats & login history (view)

### Admin (Content Manager)
✅ Semua konten management (berita, banner, galeri, video, agenda, pengumuman, laman)
✅ Semua data management (layanan, statistik, skpd, menu, dokumen, ppid)
✅ Pegawai management
✅ Settings & profil diskominfo
✅ Coverage 4G & pengaduan
✅ Visitor stats & login history (view only)
❌ **User management** (tidak bisa buat/edit/hapus user)
❌ **Delete login history**

### Penulis (Writer)
✅ Berita management (create, edit, delete own articles)
❌ Semua management lainnya

---

## 🐛 Troubleshooting

### Gambar 404 Not Found

**Check:**
1. File benar-benar ada di `public/uploads/`
2. Permissions: `chmod -R 777 public/uploads`
3. `.htaccess` tidak block uploads directory
4. APP_URL di `.env` benar

**Solution:**
```bash
# Verify file exists
ls -la public/uploads/berita/

# Test direct access
curl -I https://diskominfo.sanggau.go.id/uploads/berita/filename.jpg

# Should return 200 OK, not 403 or 404
```

### Gambar Masih Relative Path

**Check:**
```bash
# Test API response
curl https://diskominfo.sanggau.go.id/api/banner | jq '.[] | .gambar'

# Should output:
"https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"

# NOT:
"/uploads/banner/xxx.jpg"
```

**Solution:**
```bash
# Clear cache
php artisan config:clear
php artisan config:cache

# Regenerate autoload
composer dump-autoload -o

# Restart PHP-FPM (if using)
sudo systemctl restart php-fpm
```

### Admin Role 403 Forbidden

**Check:**
```bash
# List routes dengan middleware
php artisan route:list | grep admin/banner

# Should show: role:admin, NOT role:superadmin
```

**Solution:**
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache

# Verify user role in database
mysql -u root -p
SELECT id, name, email, role FROM users;
# Make sure admin user has role='admin'
```

### CORS Error

**Check:** `config/cors.php`

**Solution:**
```php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://www.diskominfo.sanggau.go.id',
    'https://your-vercel-app.vercel.app',
],
'allowed_origins_patterns' => [
    '/^https:\/\/.*\.vercel\.app$/',
],
```

Then:
```bash
php artisan config:clear
php artisan config:cache
```

---

## 📚 Documentation

- **Backend Deployment:** `DEPLOYMENT-GUIDE.md`
- **Frontend Update:** `sanggau-frontend/DEPLOYMENT-UPDATE.md`
- **Admin CMS Guide:** `ADMIN-CMS-GUIDE.md`

---

## 🎉 Done!

Setelah semua perbaikan di-deploy:
✅ Gambar muncul dengan benar di frontend (baik local maupun production)
✅ Admin bisa manage semua konten kecuali user management
✅ Superadmin tetap punya full access
✅ Backend return full URL untuk semua gambar
✅ Frontend backward compatible dengan backend lama
