# 🚨 TROUBLESHOOT - Data Tidak Loading & Server Error

## ❌ Masalah Saat Ini:

1. Data backend tidak loading di frontend
2. Login menunjukkan server error
3. API endpoints tidak berfungsi

## 🔍 DIAGNOSTIC STEP-BY-STEP:

### STEP 1: Run Full Diagnostic (WAJIB!)

**Upload file:**
```
full-diagnostic.php
```

**Akses:**
```
https://api.diskominfo.sanggau.go.id/full-diagnostic.php
```

**Ini akan check:**
- ✅ PHP environment
- ✅ Critical files (Kernel, .env, routes, etc)
- ✅ Storage folder structure
- ✅ Laravel bootstrap
- ✅ Database connection
- ✅ API endpoints
- ✅ .env configuration
- ✅ Error logs
- ✅ .htaccess configuration

**Expected result:** Semua check harus ✅ hijau.

**Jika ada ❌ merah:** Ikuti instruksi di output untuk fix.

---

### STEP 2: Check Hasil Diagnostic

Berdasarkan hasil, identifikasi masalah:

#### A. Storage Folders Missing
```
❌ storage/framework NOT FOUND
```

**Fix:**
1. Upload `fix-storage-structure.php`
2. Akses via browser
3. Run diagnostic lagi

#### B. Laravel Bootstrap Failed
```
❌ Laravel bootstrap FAILED
```

**Possible causes:**
- Composer dependencies tidak ter-install
- .env file error
- Permission issue

**Fix:**
- Check error message di output
- Verify .env file format

#### C. Database Connection Failed
```
❌ Database connection FAILED
```

**Fix:**
1. Check kredensial di .env:
   - DB_HOST=localhost (BUKAN 127.0.0.1)
   - DB_DATABASE=(nama database benar)
   - DB_USERNAME=(username benar)
   - DB_PASSWORD=(password benar)
2. Upload .env yang sudah benar
3. Clear cache
4. Run diagnostic lagi

#### D. API Endpoints Return 500
```
❌ /api/banner → HTTP 500
```

**Fix:**
1. Check section "Recent Error Logs" di diagnostic
2. Baca error message
3. Fix sesuai error
4. Clear cache
5. Test lagi

---

### STEP 3: Test Individual Components

#### Test A: Direct API Access

Buka di browser:
```
https://api.diskominfo.sanggau.go.id/api/banner
```

**Expected:** JSON array
**If Error 500:** Check laravel.log
**If 404:** Routes not working
**If blank:** CORS atau Laravel not running

#### Test B: Laravel Welcome Page

Buka di browser:
```
https://api.diskominfo.sanggau.go.id
```

**Expected:** 
- Laravel page, ATAU
- JSON response, ATAU
- Any response (not 500 error)

**If Error 500:** Laravel tidak bisa bootstrap

#### Test C: Frontend Console

Buka frontend: `https://diskominfo.sanggau.go.id`

Tekan `F12` → Console tab

Check errors:
- **CORS error** → Backend CORS config issue
- **404 Not Found** → API route not found
- **500 Server Error** → Backend Laravel error
- **Failed to fetch** → Backend tidak accessible

---

### STEP 4: Common Fixes

#### Fix 1: Clear ALL Cache

```bash
# Via clear-cache.php
1. Upload clear-cache.php
2. Akses via browser
3. Klik "Clear All Cache"
4. SKIP "Rebuild Cache" (jika error)

# Via SSH
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Via Manual
Delete files:
- bootstrap/cache/config.php
- bootstrap/cache/routes-v7.php
- bootstrap/cache/services.php
```

#### Fix 2: Verify File Upload

Check files uploaded correctly:
- `app/Http/Kernel.php` → Check DecodeBase64Input commented
- `.env` → Check DB_HOST=localhost
- `.htaccess` → Check RewriteEngine On

#### Fix 3: Permission Fix

Set permissions:
```
storage/           → 755 atau 775
bootstrap/cache/   → 755 atau 775
.env               → 644
```

---

### STEP 5: Check Laravel Log

Via File Manager, buka:
```
storage/logs/laravel.log
```

Scroll ke bawah (last 100 lines), cari:
- Lines with "ERROR"
- Lines with "EXCEPTION"
- Lines with "FATAL"

**Copy error message** dan gunakan untuk troubleshoot specific issue.

---

## 🎯 Checklist Troubleshooting:

Execute in order:

- [ ] Upload & run `full-diagnostic.php`
- [ ] Check all sections, note any ❌
- [ ] Fix storage folders (if missing)
- [ ] Verify .env configuration
- [ ] Verify database connection
- [ ] Upload Kernel.php, .env, .htaccess
- [ ] Clear ALL cache
- [ ] Run diagnostic again
- [ ] Test API endpoints directly
- [ ] Test frontend console
- [ ] Check Laravel log for specific errors
- [ ] Fix specific errors found
- [ ] Clear cache again
- [ ] Test website

---

## 🚨 Critical Files Checklist:

Files yang WAJIB ada dan correct:

```
✅ .env                     → Database credentials correct
✅ .htaccess                → RewriteEngine On
✅ app/Http/Kernel.php      → DecodeBase64Input disabled
✅ vendor/autoload.php      → Composer installed
✅ storage/framework/*      → All folders exist & writable
✅ bootstrap/cache/         → Writable
```

---

## 📞 Jika Masih Stuck:

1. **Run full-diagnostic.php**
2. **Screenshot semua sections** (terutama yang ❌)
3. **Copy last 50 lines** dari storage/logs/laravel.log
4. **Screenshot browser console** (F12 → Console) saat akses frontend
5. **Send to developer** dengan:
   - Full diagnostic screenshot
   - Laravel log excerpt
   - Browser console screenshot
   - Description of issue

---

## 💡 Quick Wins to Try:

1. **Re-upload .env** dengan kredensial benar
2. **Re-upload Kernel.php** yang sudah di-fix
3. **Clear cache** semua
4. **Fix storage folders** via fix-storage-structure.php
5. **Test API directly** di browser

Salah satu dari ini biasanya fix masalah!

---

**Priority:** CRITICAL  
**Tool:** full-diagnostic.php  
**Time:** 10-15 menit untuk full diagnostic

**START WITH full-diagnostic.php!** 🚀

---

**File:** TROUBLESHOOT-NOW.md  
**Created:** 12 Juni 2026
