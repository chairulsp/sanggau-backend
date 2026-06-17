# 🚀 QUICK REFERENCE - Deploy Commands

## 📦 BACKEND (cPanel)

### Upload Manual via File Manager cPanel
1. Login cPanel: https://sanggau.go.id:2083/
2. File Manager → public_html
3. Upload/Replace these folders:
   - `app/Models/`
   - `app/Helpers/`
   - `routes/`
4. Upload/Replace these files:
   - `composer.json`
   - `.env.production` (rename to `.env`)

### SSH Commands (COPY & PASTE)
```bash
# Login SSH
ssh username@sanggau.go.id

# Masuk directory
cd ~/public_html

# Setup .env (edit sesuai database)
cp .env.production .env
nano .env

# Install & Cache
composer install --no-dev --optimize-autoloader && \
composer dump-autoload -o && \
php artisan config:clear && \
php artisan cache:clear

# Permissions
chmod -R 777 public/uploads

# Test API
curl https://diskominfo.sanggau.go.id/api/banner
```

### Verify Backend
```bash
# Harus return full URL:
curl https://diskominfo.sanggau.go.id/api/banner | grep -o 'https://[^"]*\.jpg'
```

---

## 🌐 FRONTEND (Git/Vercel)

### Git Commands (COPY & PASTE)
```bash
cd sanggau-frontend

# Add, Commit, Push
git add . && \
git commit -m "fix: Gambar tidak muncul & admin permissions" && \
git push origin main
```

### Verify Vercel
1. Check: https://vercel.com/dashboard
2. Status harus: ✅ Ready
3. Test: https://your-domain.vercel.app

---

## 🧪 TESTING (COPY & PASTE)

### Test Backend API
```bash
# Banner
curl https://diskominfo.sanggau.go.id/api/banner | jq '.[0].gambar'

# Berita
curl https://diskominfo.sanggau.go.id/api/berita | jq '.[0].gambar'

# Direct image
curl -I https://diskominfo.sanggau.go.id/uploads/banner/test.jpg
```

### Test Frontend (Browser)
```
1. Open: https://your-domain.vercel.app
2. F12 → Console → No errors
3. F12 → Network → Img tab → All 200 OK
4. Homepage → Banner images load
5. /berita → Images load
6. /galeri → Photos load
```

### Test CMS Upload
```
1. Login: https://diskominfo.sanggau.go.id/admin
2. Berita → Tambah Baru
3. Upload gambar (file JPG/PNG)
4. Simpan
5. Check frontend → Gambar muncul ✅
```

---

## 🔧 TROUBLESHOOTING (QUICK FIX)

### Gambar 404
```bash
ssh username@sanggau.go.id
chmod -R 777 public/uploads
```

### API Masih Relative Path
```bash
ssh username@sanggau.go.id
cd ~/public_html
php artisan config:clear
php artisan cache:clear
composer dump-autoload -o
```

### CORS Error
```bash
ssh username@sanggau.go.id
cd ~/public_html
nano config/cors.php
# Tambah domain Vercel di allowed_origins
php artisan config:cache
```

### Admin 403
```bash
ssh username@sanggau.go.id
cd ~/public_html
php artisan route:clear
# Check user role: SELECT role FROM users WHERE id=X;
```

---

## 📱 TEST CHECKLIST

### Backend ✅
- [ ] API return full URL
- [ ] Direct image accessible
- [ ] Admin bisa akses banner
- [ ] Admin TIDAK bisa akses pengguna

### Frontend ✅
- [ ] Homepage banner load
- [ ] Berita images load
- [ ] Galeri photos load
- [ ] No console errors
- [ ] No CORS errors

### Integration ✅
- [ ] Upload gambar di CMS
- [ ] Gambar muncul di frontend
- [ ] Edit gambar di CMS
- [ ] Update muncul di frontend

---

## 🆘 EMERGENCY ROLLBACK

### Backend
```bash
ssh username@sanggau.go.id
cd ~/public_html
cp .env.backup .env
php artisan config:clear
```

### Frontend
```bash
cd sanggau-frontend
git revert HEAD
git push origin main
```

---

## 📞 CONTACTS

- cPanel: https://sanggau.go.id:2083/
- Vercel: https://vercel.com/dashboard
- GitHub: https://github.com/your-repo

---

**SIAP DEPLOY! 🚀**
