# 🚨 FIX: Backend API Tidak Loading di Frontend

## ❌ Masalah:

Frontend tidak bisa mengambil data dari backend:
- Data backend tidak muncul di frontend
- Tidak bisa login
- Homepage kosong (no banners, berita, dll)

Frontend menggunakan API endpoint:
```
https://api.diskominfo.sanggau.go.id/api
```

## 🔍 Kemungkinan Penyebab:

1. **Laravel belum bisa jalan** karena folder `storage/framework` hilang
2. **Routes belum di-cache** setelah deployment
3. **.htaccess tidak dikonfigurasi dengan benar**
4. **CORS configuration** memblok request dari frontend
5. **Database belum terkoneksi** dengan benar

## ✅ SOLUSI STEP-BY-STEP:

### STEP 1: Fix Storage Structure (WAJIB!)

Upload dan jalankan:
```
https://api.diskominfo.sanggau.go.id/fix-storage-structure.php
```

Pastikan semua folder terbuat dengan benar.

---

### STEP 2: Test API Endpoints

Upload dan jalankan:
```
https://api.diskominfo.sanggau.go.id/test-api.php
```

File ini akan test semua endpoint yang digunakan frontend:
- ✅ `/api/banner`
- ✅ `/api/berita`
- ✅ `/api/settings`
- ✅ `/api/pengumuman`
- ✅ `/api/agenda`
- ✅ `/api/profil-pimpinan`
- ✅ `/api/profil-diskominfo`

**Expected Result:** Semua endpoint harus return HTTP 200 dengan data JSON.

---

### STEP 3: Check Laravel Running

Akses langsung domain backend di browser:
```
https://api.diskominfo.sanggau.go.id
```

**Expected Result:** 
- Laravel welcome page, ATAU
- JSON response (jika configured untuk JSON), ATAU
- Redirect ke route lain

**Jika Error 500:**
- Storage structure belum fix
- Database belum terkoneksi
- Check `storage/logs/laravel.log`

---

### STEP 4: Verify .htaccess

File `.htaccess` harus ada di **ROOT FOLDER** dengan content ini:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Jika tidak ada:** Upload file `.htaccess` yang benar dari local.

---

### STEP 5: Check CORS Configuration

File `config/cors.php` harus allow frontend domain:

```php
'allowed_origins' => [
    'https://diskominfo.sanggau.go.id',
    'https://www.diskominfo.sanggau.go.id',
    'https://api.diskominfo.sanggau.go.id',
    'http://localhost:3000', // for local dev
],
```

Jika perlu update, setelah update **clear cache!**

---

### STEP 6: Rebuild Cache & Routes

Akses lagi:
```
https://api.diskominfo.sanggau.go.id/clear-cache.php
```

Klik:
1. "Clear All Cache"
2. **"Rebuild Cache"** ← PENTING!

---

### STEP 7: Test dari Frontend

**Test di Browser Console:**

Buka frontend: `https://diskominfo.sanggau.go.id`

Tekan `F12` → Console tab

Run command:
```javascript
fetch('https://api.diskominfo.sanggau.go.id/api/banner')
  .then(r => r.json())
  .then(d => console.log('✅ API working:', d))
  .catch(e => console.error('❌ API error:', e))
```

**Expected Result:** Console show data array dari backend.

**Jika Error:**
- `CORS error` → Check CORS config
- `404 Not Found` → Routes belum di-cache
- `500 Server Error` → Check laravel.log
- `Failed to fetch` → Domain tidak bisa diakses

---

## 🔧 Manual Troubleshooting:

### Check Routes Available

Via SSH atau cPanel Terminal:
```bash
cd /home/diskominfo/public_html
php artisan route:list | grep api
```

Should show routes like:
```
GET|HEAD  api/banner
GET|HEAD  api/berita
GET|HEAD  api/settings
...
```

**Jika tidak ada routes:**
```bash
php artisan route:cache
php artisan config:cache
```

### Check Laravel Log

Via File Manager, buka:
```
storage/logs/laravel.log
```

Cari error terbaru (paling bawah file).

**Common errors:**
- `Please provide a valid cache path` → Storage structure belum fix
- `SQLSTATE[HY000]` → Database error
- `Class not found` → Composer autoload error

### Check Database

Via `server-troubleshooting.php`:
```
https://api.diskominfo.sanggau.go.id/server-troubleshooting.php
```

Section "Database Connection Test" harus ✅

---

## 📋 Diagnostic Checklist:

Run these tests in order:

- [ ] `fix-storage-structure.php` executed
- [ ] All storage folders exist
- [ ] `test-api.php` shows all endpoints working
- [ ] `https://api.diskominfo.sanggau.go.id` accessible
- [ ] `.htaccess` file present and correct
- [ ] CORS config includes frontend domain
- [ ] Cache cleared and rebuilt
- [ ] Database connected (via server-troubleshooting.php)
- [ ] Routes cached (`php artisan route:cache`)
- [ ] Browser console fetch test works
- [ ] Frontend loads data (banners, berita visible)

---

## 🎯 Expected Final State:

After all fixes:

✅ **Backend API:**
- `https://api.diskominfo.sanggau.go.id/api/banner` → Returns JSON array
- `https://api.diskominfo.sanggau.go.id/api/berita` → Returns JSON array
- All other endpoints working

✅ **Frontend:**
- Homepage loads banners (hero slider)
- Berita terbaru visible
- Layanan digital cards visible
- Login form accessible
- Can login with admin credentials

✅ **No Errors:**
- Browser console clean (no CORS or 404 errors)
- Laravel log clean (no critical errors)
- Frontend Network tab shows successful API calls

---

## 🚨 Critical Files to Upload:

```
1. fix-storage-structure.php   (create folders)
2. app/Http/Kernel.php          (middleware fix)
3. .env                         (database config)
4. .htaccess                    (rewrite rules)
5. clear-cache.php              (clear cache)
6. test-api.php                 (test endpoints)
```

---

## 📞 Still Not Working?

1. **Upload all diagnostic files:**
   - `fix-storage-structure.php`
   - `server-troubleshooting.php`
   - `test-api.php`

2. **Run all diagnostic tests**

3. **Screenshot results:**
   - test-api.php output
   - server-troubleshooting.php output
   - Browser console errors

4. **Check Laravel log:**
   - `storage/logs/laravel.log`
   - Copy last 50 lines

5. **Send to developer with:**
   - All screenshots
   - Log output
   - Description of issue

---

**REMEMBER: Delete all test files after troubleshooting!**

Files to delete:
- ❌ `fix-storage-structure.php`
- ❌ `clear-cache.php`
- ❌ `server-troubleshooting.php`
- ❌ `test-api.php`

---

**Priority:** HIGH  
**Estimated Time:** 10-15 menit  
**Difficulty:** Medium

**START WITH STEP 1 AND WORK THROUGH SEQUENTIALLY!** 🚀
