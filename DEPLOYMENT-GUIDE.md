# Panduan Deployment Backend ke cPanel

## Masalah yang Diperbaiki

### 1. **Gambar Tidak Muncul di Frontend**
**Penyebab:**
- Path gambar di database masih relatif (`/uploads/berita/xxx.jpg`)
- Frontend tidak bisa mengakses gambar karena tidak ada full URL
- Konfigurasi APP_URL masih localhost

**Solusi:**
- Menambahkan accessor di Model untuk otomatis convert path relatif ke full URL
- Update APP_URL di .env production
- Helper function `full_asset_url()` dan `format_image_url()`

### 2. **Role Admin Terbatas**
**Penyebab:**
- Banyak resource hanya accessible oleh superadmin
- Admin tidak bisa manage banner, statistik, layanan, dll

**Solusi:**
- Memindahkan hampir semua resource ke `role:admin` middleware
- Hanya **Manajemen Pengguna** yang khusus superadmin
- Admin sekarang bisa akses semua kecuali user management

---

## Langkah Deployment ke cPanel

### 1. Upload Backend ke cPanel

```bash
# Compress project (exclude vendor, node_modules, .git)
zip -r sanggau-backend.zip . -x "vendor/*" "node_modules/*" ".git/*" "storage/logs/*"

# Upload via File Manager cPanel atau FTP
# Extract ke public_html atau subdirectory
```

### 2. Setup .env Production

```bash
# Di cPanel File Manager, copy .env.production ke .env
cp .env.production .env

# Edit .env sesuai kredensial cPanel:
APP_URL=https://diskominfo.sanggau.go.id
DB_DATABASE=cpanel_username_sanggau
DB_USERNAME=cpanel_username
DB_PASSWORD=your_database_password
```

### 3. Install Dependencies via SSH Terminal

```bash
# Login SSH cPanel
ssh username@diskominfo.sanggau.go.id

# Masuk ke directory project
cd public_html

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Generate autoload files (penting untuk helpers)
composer dump-autoload -o

# Run migrations
php artisan migrate --force

# Clear & cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
chmod -R 777 public/uploads
```

### 4. Setup Public Directory

cPanel biasanya menggunakan `public_html` sebagai document root. Ada 2 cara:

#### Opsi A: Symbolic Link (Recommended)
```bash
# Pindahkan isi public/ ke public_html/
mv public/* public_html/
mv public/.htaccess public_html/

# Update index.php
# Edit public_html/index.php
# Ubah require __DIR__.'/../vendor/autoload.php';
# Menjadi require __DIR__.'/../vendor/autoload.php';
```

#### Opsi B: Change Document Root
Di cPanel > Domains > pilih domain > ubah Document Root ke `public_html/public`

### 5. Setup Storage Symbolic Link

```bash
# Buat symbolic link untuk storage
php artisan storage:link

# Manual jika command gagal:
cd public_html/public
ln -s ../storage/app/public storage
```

### 6. Setup .htaccess untuk cPanel

Pastikan `.htaccess` di `public_html/` (atau `public/`):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
    
    # Laravel routing
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>

# Allow uploads directory
<Directory "uploads">
    Options +Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

### 7. Verify Image URLs

Setelah deployment, test API endpoint:

```bash
# Test banner API
curl https://diskominfo.sanggau.go.id/api/banner

# Response harus mengandung full URL:
{
  "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
}

# Test berita API
curl https://diskominfo.sanggau.go.id/api/berita

# Test direct image access
curl -I https://diskominfo.sanggau.go.id/uploads/berita/xxx.jpg
# Harus return 200 OK
```

---

## Testing Checklist

- [ ] Backend accessible via https://diskominfo.sanggau.go.id
- [ ] API endpoint `/api/berita` return full URL gambar
- [ ] API endpoint `/api/banner` return full URL gambar
- [ ] API endpoint `/api/galeri` return full URL gambar
- [ ] Upload gambar baru via CMS admin
- [ ] Gambar muncul di frontend setelah upload
- [ ] Admin bisa akses banner, statistik, layanan, menu
- [ ] Admin TIDAK bisa akses manajemen pengguna
- [ ] Superadmin bisa akses semua termasuk manajemen pengguna
- [ ] CORS working dari Vercel frontend
- [ ] Direct image URL accessible

---

## Troubleshooting

### Gambar Masih Tidak Muncul

**1. Check APP_URL di .env production:**
```bash
cat .env | grep APP_URL
# Harus: APP_URL=https://diskominfo.sanggau.go.id
```

**2. Clear config cache:**
```bash
php artisan config:clear
php artisan config:cache
```

**3. Verify uploads directory permissions:**
```bash
ls -la public/uploads
# Harus 755 atau 777
chmod -R 777 public/uploads
```

**4. Check .htaccess rules:**
Pastikan tidak ada `Deny from all` di `public/uploads/.htaccess`

**5. Test direct image access:**
```bash
curl -I https://diskominfo.sanggau.go.id/uploads/berita/test.jpg
# Harus return 200, bukan 403 atau 404
```

### CORS Errors

**1. Verify CORS config:**
```php
// config/cors.php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://sanggau-frontend.vercel.app', // Frontend Vercel URL
],
'allowed_origins_patterns' => [
    '/^https:\/\/.*\.vercel\.app$/',
],
```

**2. Clear config cache:**
```bash
php artisan config:clear
php artisan config:cache
```

### Admin Role Tidak Bisa Akses Resource

**1. Verify routes:**
```bash
php artisan route:list | grep admin
```

**2. Check middleware:**
Routes banner, statistik, dll harus di `role:admin`, bukan `role:superadmin`

**3. Clear route cache:**
```bash
php artisan route:clear
php artisan route:cache
```

---

## Frontend Update Required

Frontend perlu update untuk handle full URL dari backend:

```typescript
// Frontend tidak perlu lagi prepend BACKEND_ORIGIN
// Karena backend sudah return full URL

// BEFORE:
const imageUrl = `${BACKEND_ORIGIN}${berita.gambar}`;

// AFTER:
const imageUrl = berita.gambar; // Sudah full URL dari backend
```

Lihat `FRONTEND-UPDATE.md` untuk detail lengkap.
