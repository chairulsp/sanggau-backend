# 🚀 SIAP DEPLOY - Panduan Lengkap

## ✅ Status: READY FOR PRODUCTION

Semua perbaikan sudah selesai dan siap untuk di-deploy:
- ✅ **Gambar akan muncul** di frontend dengan full URL
- ✅ **Admin bisa manage semua** kecuali user management
- ✅ **Upload file gambar** di CMS tetap normal (tidak ada perubahan cara upload)
- ✅ **Backward compatible** - frontend tetap work dengan backend lama maupun baru

---

## 📦 BACKEND - Upload ke cPanel

### Langkah 1: Persiapan File

**File yang perlu di-upload ke cPanel:**

```
sanggau-backend/
├── app/
│   ├── Http/
│   ├── Models/          ← MODIFIED (Berita, Banner, Galeri)
│   └── Helpers/         ← NEW (helpers.php)
├── routes/
│   └── api.php          ← MODIFIED (role permissions)
├── composer.json        ← MODIFIED (autoload helpers)
├── .env.production      ← NEW (template untuk production)
└── public/
    └── uploads/         ← EXISTING (pastikan tetap ada)
```

**❌ JANGAN upload folder:**
- `vendor/` (akan di-generate via composer di server)
- `node_modules/`
- `storage/logs/*` (akan di-generate otomatis)
- `.git/`

### Langkah 2: Compress & Upload

**Di Local:**
```bash
# Compress project (exclude vendor, logs)
cd sanggau-backend
tar -czf sanggau-backend.tar.gz \
  --exclude=vendor \
  --exclude=node_modules \
  --exclude=.git \
  --exclude=storage/logs/* \
  .
```

Atau via File Manager cPanel:
1. Upload folder `app/`, `routes/`, `config/`, `database/`
2. Upload file `composer.json`, `.env.production`
3. **JANGAN hapus** `public/uploads/` yang sudah ada

### Langkah 3: Setup via SSH cPanel

```bash
# 1. Login SSH
ssh username@sanggau.go.id

# 2. Masuk ke directory project
cd ~/public_html
# atau cd ~/domains/diskominfo.sanggau.go.id/public_html

# 3. Backup .env lama
cp .env .env.backup

# 4. Setup .env baru
cp .env.production .env
nano .env

# Edit:
APP_URL=https://diskominfo.sanggau.go.id
DB_HOST=localhost
DB_DATABASE=sanggau_db_production
DB_USERNAME=cpanel_username
DB_PASSWORD=your_password

# 5. Install/Update dependencies
composer install --no-dev --optimize-autoloader
composer dump-autoload -o

# 6. Run migrations (jika ada perubahan DB)
php artisan migrate --force

# 7. Clear & Cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 8. Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
chmod -R 777 public/uploads

# 9. Create symlink storage (jika belum)
php artisan storage:link
```

### Langkah 4: Verify Backend

```bash
# Test API banner
curl https://diskominfo.sanggau.go.id/api/banner

# Response harus seperti ini:
[
  {
    "id": 1,
    "judul": "Banner Test",
    "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
    # ↑ FULL URL, bukan /uploads/banner/xxx.jpg
  }
]

# Test direct image access
curl -I https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg
# Harus return: HTTP/1.1 200 OK

# Test admin access (perlu token)
curl -H "Authorization: Bearer YOUR_TOKEN" \
  https://diskominfo.sanggau.go.id/api/admin/banner
# Admin harus bisa akses (200 OK)

curl -H "Authorization: Bearer ADMIN_TOKEN" \
  https://diskominfo.sanggau.go.id/api/admin/pengguna
# Admin harus TIDAK bisa akses (403 Forbidden)
```

---

## 🌐 FRONTEND - Deploy ke Git/Vercel

### Langkah 1: Commit & Push

```bash
cd sanggau-frontend

# Check status
git status

# Add all changes
git add .

# Commit
git commit -m "fix: Perbaiki gambar tidak muncul & update admin permissions

- Update image URL handling dengan resolveImageUrl helper
- Backward compatible dengan backend lama dan baru
- Fix admin role permissions untuk akses semua resource
- Update homepage, berita, dan galeri pages"

# Push ke GitHub
git push origin main
```

### Langkah 2: Vercel Auto Deploy

Vercel akan otomatis detect push ke GitHub dan trigger deployment.

**Atau manual deploy:**
```bash
# Install Vercel CLI (jika belum)
npm i -g vercel

# Deploy
vercel --prod
```

### Langkah 3: Verify Environment Variables di Vercel

1. Buka Vercel Dashboard: https://vercel.com/dashboard
2. Pilih project: `sanggau-frontend`
3. Settings → Environment Variables
4. Pastikan ada:
   ```
   NEXT_PUBLIC_API_URL=https://diskominfo.sanggau.go.id/api
   ```
5. Jika tidak ada atau salah, update dan redeploy

### Langkah 4: Verify Frontend

```bash
# 1. Buka browser
https://your-frontend.vercel.app

# 2. Check homepage
- Banner images harus muncul
- Tidak ada error di console (F12 → Console)
- Tidak ada CORS error

# 3. Check berita page
- Gambar berita harus muncul
- Thumbnail berita harus load

# 4. Check galeri page
- Foto-foto harus muncul
- Video thumbnails harus muncul

# 5. Check Network tab (F12 → Network → Img)
- Image URLs harus full: https://diskominfo.sanggau.go.id/uploads/...
- Status harus: 200 OK (bukan 404 atau 403)
```

---

## 🧪 TESTING LENGKAP

### Test 1: Upload Gambar Baru di CMS

1. Login ke CMS admin: https://diskominfo.sanggau.go.id/admin
2. Pilih menu **Berita** → **Tambah Baru**
3. Isi form:
   - Judul: "Test Upload Gambar"
   - Konten: "Testing..."
   - **Upload file gambar** (JPG/PNG)
   - Kategori: "Berita"
   - Status: "Published"
4. Klik **Simpan**
5. Buka frontend: https://your-frontend.vercel.app/berita
6. **VERIFY:** Berita baru muncul dengan gambar

### Test 2: Edit Gambar Existing

1. Login CMS → Berita → Pilih berita lama
2. Klik **Edit**
3. **Upload gambar baru** (ganti gambar lama)
4. Klik **Update**
5. Buka frontend → Refresh
6. **VERIFY:** Gambar berubah ke yang baru

### Test 3: Admin Access

**Login sebagai Admin:**
1. Login dengan akun role: `admin`
2. Coba akses menu:
   - ✅ Banner → Harus bisa akses
   - ✅ Galeri → Harus bisa akses
   - ✅ Video → Harus bisa akses
   - ✅ Statistik → Harus bisa akses
   - ✅ Layanan → Harus bisa akses
   - ✅ Menu → Harus bisa akses
   - ❌ **Pengguna** → Harus **TIDAK** bisa akses (403 atau redirect)

**Login sebagai Superadmin:**
1. Login dengan akun role: `superadmin`
2. Coba akses menu:
   - ✅ Semua menu harus bisa diakses
   - ✅ Pengguna → Harus bisa akses
   - ✅ Bisa create/edit/delete users

### Test 4: Cross-Device

Test dari berbagai device:
- [ ] Desktop Chrome
- [ ] Desktop Firefox
- [ ] Desktop Safari
- [ ] Mobile Android Chrome
- [ ] Mobile iOS Safari
- [ ] Tablet

### Test 5: Performance

- [ ] Gambar load dalam < 3 detik
- [ ] Homepage load dalam < 5 detik
- [ ] Tidak ada error 404 di Network tab
- [ ] Tidak ada CORS error di Console

---

## 🔧 TROUBLESHOOTING

### ❌ Gambar 404 Not Found

**Diagnosa:**
```bash
# Check file exists di server
ssh username@sanggau.go.id
ls -la public/uploads/berita/
```

**Solusi:**
```bash
# Set permissions
chmod -R 777 public/uploads

# Check .htaccess di uploads
cat public/uploads/.htaccess
# Pastikan TIDAK ada: Deny from all

# Jika ada, edit:
nano public/uploads/.htaccess
# Hapus baris "Deny from all"
```

### ❌ Gambar Masih Relative Path di API

**Diagnosa:**
```bash
curl https://diskominfo.sanggau.go.id/api/banner | jq '.[0].gambar'
# Jika output: "/uploads/banner/xxx.jpg" ← SALAH
# Harus: "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
```

**Solusi:**
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Verify APP_URL
cat .env | grep APP_URL
# Harus: APP_URL=https://diskominfo.sanggau.go.id

# Regenerate autoload
composer dump-autoload -o

# Restart PHP-FPM (jika pakai)
sudo systemctl restart php-fpm
# atau restart Apache
sudo systemctl restart httpd
```

### ❌ CORS Error di Frontend

**Error di Console:**
```
Access to fetch at 'https://diskominfo.sanggau.go.id/api/banner' 
from origin 'https://your-frontend.vercel.app' has been blocked by CORS policy
```

**Solusi Backend:**
```bash
# Edit config/cors.php
nano config/cors.php
```

```php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://www.diskominfo.sanggau.go.id',
    'https://your-frontend.vercel.app',  // ← Tambahkan domain Vercel
],

'allowed_origins_patterns' => [
    '/^https:\/\/.*\.vercel\.app$/',  // ← Allow semua *.vercel.app
],
```

```bash
# Clear cache
php artisan config:clear
php artisan config:cache
```

### ❌ Admin 403 Forbidden

**Error:** Admin tidak bisa akses banner/statistik/dll

**Solusi:**
```bash
# Verify routes
php artisan route:list | grep admin/banner
# Harus show: middleware: role:admin

# Check user role di database
mysql -u root -p
SELECT id, name, email, role FROM users;
# Pastikan user admin punya role='admin' bukan 'user'

# Clear route cache
php artisan route:clear

# Verify di browser
# Logout → Login ulang
```

### ❌ Upload Gambar Error 500

**Solusi:**
```bash
# Check upload limits di php.ini
php -i | grep upload_max
php -i | grep post_max

# Edit php.ini (di cPanel → MultiPHP INI Editor)
upload_max_filesize = 10M
post_max_size = 10M
memory_limit = 256M

# Restart Apache
sudo systemctl restart httpd

# Check permissions
chmod -R 777 public/uploads
```

---

## ✅ FINAL CHECKLIST

### Pre-Deploy
- [x] Semua file sudah dicommit
- [x] .env.production sudah dibuat
- [x] Documentation lengkap
- [x] Backup database existing

### Backend Deploy
- [ ] Upload files ke cPanel
- [ ] Setup .env production
- [ ] Run composer install
- [ ] Run migrations
- [ ] Set permissions
- [ ] Clear cache
- [ ] Test API endpoints
- [ ] Test image URLs (full URL)
- [ ] Test admin login & permissions

### Frontend Deploy
- [ ] Push to GitHub
- [ ] Vercel auto-deploy success
- [ ] Environment variables correct
- [ ] Homepage images load
- [ ] Berita images load
- [ ] Galeri images load
- [ ] No CORS errors
- [ ] No console errors

### Integration Test
- [ ] Upload berita baru dengan gambar di CMS
- [ ] Gambar muncul di frontend
- [ ] Edit berita, ganti gambar
- [ ] Gambar update di frontend
- [ ] Admin bisa akses banner/statistik/dll
- [ ] Admin TIDAK bisa akses user management
- [ ] Superadmin bisa akses semua
- [ ] Test dari mobile device
- [ ] Test dari desktop

---

## 📞 Support

Jika ada masalah setelah deploy:

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Apache error log:**
   ```bash
   tail -f /var/log/httpd/error_log
   ```

3. **Enable debug mode sementara:**
   ```bash
   # .env
   APP_DEBUG=true
   LOG_LEVEL=debug
   
   # Jangan lupa disable setelah selesai debug
   ```

4. **Test API dengan Postman/Insomnia** untuk isolate frontend vs backend issue

---

## 🎉 DONE!

Setelah semua checklist ✅, website siap production dengan:
- ✅ Gambar muncul sempurna di frontend
- ✅ Admin bisa manage semua konten
- ✅ Upload gambar di CMS work normal
- ✅ Performance optimal
- ✅ Security terjaga (superadmin only untuk user management)

**Selamat Deploy! 🚀**
