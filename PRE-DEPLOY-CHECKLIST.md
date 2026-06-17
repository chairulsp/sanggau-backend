# ✅ PRE-DEPLOY CHECKLIST

## Sebelum Deploy - Pastikan Semua Ini Sudah Dicek

---

## 📦 BACKEND CHECKLIST

### 1. Files Ready
- [ ] `app/Models/Berita.php` - sudah ada accessor getGambarAttribute()
- [ ] `app/Models/Banner.php` - sudah ada accessor getGambarAttribute()
- [ ] `app/Models/Galeri.php` - sudah ada accessor getGambarAttribute()
- [ ] `app/Helpers/helpers.php` - file exists
- [ ] `routes/api.php` - role:admin untuk semua resource kecuali pengguna
- [ ] `composer.json` - autoload helpers.php
- [ ] `.env.production` - template exists

### 2. Local Testing
- [ ] `composer dump-autoload -o` - success
- [ ] `php artisan config:clear` - success
- [ ] Tidak ada syntax error di semua file yang diubah
- [ ] Test via Postman/Insomnia (jika ada):
  - [ ] GET /api/banner - return data dengan full URL
  - [ ] GET /api/berita - return data dengan full URL
  - [ ] GET /api/galeri - return data dengan full URL

### 3. Database Backup
- [ ] **PENTING:** Backup database production SEBELUM deploy
  ```bash
  mysqldump -u user -p sanggau_db > backup_$(date +%Y%m%d).sql
  ```

### 4. Upload Package Ready
- [ ] Compress atau siapkan files untuk upload:
  - `app/Models/`
  - `app/Helpers/`
  - `routes/api.php`
  - `composer.json`
  - `.env.production`
- [ ] **JANGAN** include:
  - `vendor/`
  - `node_modules/`
  - `.git/`
  - `storage/logs/*`

---

## 🌐 FRONTEND CHECKLIST

### 1. Files Ready
- [ ] `src/lib/api.ts` - ada function resolveImageUrl()
- [ ] `src/app/(public)/page.tsx` - pakai resolveImageUrl()
- [ ] `src/app/(public)/berita/page.tsx` - pakai resolveImageUrl()
- [ ] `src/app/(public)/galeri/page.tsx` - pakai resolveImageUrl()
- [ ] `GIT-COMMIT-GUIDE.md` - exists
- [ ] `DEPLOYMENT-UPDATE.md` - exists

### 2. Local Testing
- [ ] `npm run dev` - berjalan tanpa error
- [ ] `npm run build` - build success
- [ ] `npm run lint` - no errors
- [ ] Test di browser:
  - [ ] Homepage load
  - [ ] Images load (dengan backend mock jika perlu)
  - [ ] No console errors
  - [ ] No TypeScript errors

### 3. Git Ready
- [ ] Git status clean (semua changes committed)
- [ ] `.gitignore` proper (tidak commit .env.local, node_modules, dll)
- [ ] Commit message sudah disiapkan (lihat GIT-COMMIT-GUIDE.md)
- [ ] Remote repository accessible

---

## 🔐 CREDENTIALS CHECKLIST

### Backend (cPanel)
- [ ] SSH username: _______________
- [ ] SSH password: _______________
- [ ] cPanel URL: https://sanggau.go.id:2083/
- [ ] Database name: _______________
- [ ] Database username: _______________
- [ ] Database password: _______________
- [ ] Document root path: _______________ (e.g., ~/public_html)

### Frontend (Vercel)
- [ ] Vercel account email: _______________
- [ ] GitHub repository URL: _______________
- [ ] Vercel project name: _______________
- [ ] Production URL: _______________

---

## 📋 DEPLOYMENT PLAN

### Step 1: Backend Deploy (Estimasi 20-30 menit)
- [ ] Backup database production ✅
- [ ] Upload files ke cPanel
- [ ] Setup .env production
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `composer dump-autoload -o`
- [ ] Run `php artisan config:clear`
- [ ] Run `php artisan cache:clear`
- [ ] Set permissions `chmod -R 777 public/uploads`
- [ ] Test API: `curl https://diskominfo.sanggau.go.id/api/banner`
- [ ] Verify image URL adalah full URL (bukan relative)

### Step 2: Frontend Deploy (Estimasi 5-10 menit)
- [ ] `git add .`
- [ ] `git commit -m "fix: ..."` (lihat GIT-COMMIT-GUIDE.md)
- [ ] `git push origin main`
- [ ] Monitor Vercel dashboard
- [ ] Wait for build success
- [ ] Test production URL

### Step 3: Integration Testing (Estimasi 15-20 menit)
- [ ] Homepage images load ✅
- [ ] Berita images load ✅
- [ ] Galeri images load ✅
- [ ] Upload berita baru dengan gambar di CMS
- [ ] Verify gambar muncul di frontend
- [ ] Test admin login & permissions
- [ ] Test dari mobile device
- [ ] No console errors
- [ ] No CORS errors

---

## 🧪 POST-DEPLOY VERIFICATION

### Backend API
```bash
# Test banner
curl https://diskominfo.sanggau.go.id/api/banner | jq '.[0].gambar'
# Expected: "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"

# Test berita
curl https://diskominfo.sanggau.go.id/api/berita | jq '.[0].gambar'
# Expected: "https://diskominfo.sanggau.go.id/uploads/berita/xxx.jpg"

# Test direct image access
curl -I https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg
# Expected: HTTP/1.1 200 OK
```

### Frontend
- [ ] Open: https://your-frontend.vercel.app
- [ ] F12 → Console → No errors
- [ ] F12 → Network → Img tab → All 200 OK
- [ ] Homepage banner loads
- [ ] Berita page images load
- [ ] Galeri page images load

### CMS Admin
- [ ] Login sebagai **admin**: _______________ / _______________
- [ ] Bisa akses: Banner ✅
- [ ] Bisa akses: Statistik ✅
- [ ] Bisa akses: Layanan ✅
- [ ] Bisa akses: Galeri ✅
- [ ] **TIDAK** bisa akses: Pengguna ❌ (harus 403)

- [ ] Login sebagai **superadmin**: _______________ / _______________
- [ ] Bisa akses: Pengguna ✅
- [ ] Bisa create/edit/delete user ✅

### Upload Test
- [ ] Upload berita baru dengan gambar
- [ ] Simpan
- [ ] Refresh frontend
- [ ] Gambar muncul ✅

---

## 🚨 ROLLBACK PLAN (Jika Ada Masalah)

### Backend Rollback
```bash
ssh username@sanggau.go.id
cd ~/public_html

# Restore .env backup
cp .env.backup .env

# Restore database (jika perlu)
mysql -u user -p sanggau_db < backup_20260604.sql

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Frontend Rollback
```bash
cd sanggau-frontend
git log --oneline  # Cari commit terakhir yang bagus
git revert HEAD    # Revert commit terakhir
git push origin main
```

---

## 📞 EMERGENCY CONTACTS

- **Developer:** _______________
- **Server Admin:** _______________
- **Database Admin:** _______________
- **Emergency Hotline:** _______________

---

## 📝 NOTES

Tulis catatan penting di sini:

```
Tanggal Deploy: _______________
Waktu Mulai: _______________
Waktu Selesai: _______________

Issues yang ditemukan:
- 
- 
- 

Solusi yang digunakan:
- 
- 
- 

Follow-up yang diperlukan:
- 
- 
- 
```

---

## ✅ FINAL CHECK

Sebelum declare "Deploy Success":

- [ ] Backend API return full URL untuk gambar ✅
- [ ] Frontend images load sempurna ✅
- [ ] Admin bisa akses semua resource kecuali user management ✅
- [ ] Superadmin bisa akses semua termasuk user management ✅
- [ ] Upload gambar baru di CMS work ✅
- [ ] Gambar baru muncul di frontend ✅
- [ ] No errors di console ✅
- [ ] No CORS errors ✅
- [ ] Tested dari desktop ✅
- [ ] Tested dari mobile ✅
- [ ] Performance OK (load < 5 detik) ✅
- [ ] Database backup tersimpan aman ✅

---

## 🎉 DEPLOY SUCCESS!

Jika semua checklist di atas ✅, maka:

**DEPLOYMENT BERHASIL! 🚀**

Dokumentasi:
- Simpan file backup database
- Simpan catatan issues & solusi
- Update dokumentasi jika ada perubahan
- Inform stakeholders bahwa deploy sukses

---

**Siap Deploy?** 
**Pastikan SEMUA checklist sudah ✅**
**Good Luck! 🚀**
