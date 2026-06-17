# 🔧 FIX BANNER UPLOAD GAGAL

## 🔴 MASALAH

Error saat upload banner:
```
❌ The given data was invalid. (gambar: The gambar failed to upload.)
```

**Update berita, profil, menu → ✅ BERHASIL**
**Update banner → ❌ GAGAL**

---

## 🎯 PENYEBAB

Gambar gagal diupload ke server karena:
1. Folder `uploads/banner/` tidak ada atau tidak writable
2. PHP upload limits terlalu kecil
3. Path folder salah

---

## ✅ SOLUSI - LANGKAH LENGKAP

### **STEP 1: Fix Folder Permissions di cPanel** ⚠️ PALING PENTING!

1. **Login cPanel → File Manager**

2. **Navigate ke:** `/home/diskominfo/public_html/public/uploads/`

3. **Cek folder `banner/`:**
   - ✅ **Jika ADA** → Lanjut ke step 4
   - ❌ **Jika TIDAK ADA** → Buat folder baru:
     * Masuk folder `uploads/`
     * Klik **"+ Folder"**
     * Nama: `banner`
     * Create New Folder

4. **Set Permissions folder `banner/`:**
   - Klik kanan folder `banner/`
   - **"Change Permissions"**
   - Input: **0775**
   - **ATAU** tick checkbox:
     * Owner: Read, Write, Execute (RWX)
     * Group: Read, Write, Execute (RWX)
     * World: Read, Execute (RX)
   - **Apply Changes**

5. **Set Permissions parent folder `uploads/` juga:**
   - Naik ke folder `public/`
   - Klik kanan folder `uploads/`
   - Change Permissions → **0775**
   - Apply

---

### **STEP 2: Increase PHP Upload Limits**

Gambar yang diupload mungkin melebihi limit PHP default.

#### **Option A: Via cPanel MultiPHP INI Editor** ⭐ RECOMMENDED

1. **Exit File Manager**, kembali ke cPanel Home
2. **Cari: "MultiPHP INI Editor"** (di section Software)
3. **Klik MultiPHP INI Editor**
4. **Pilih tab "Editor Mode"**
5. **Select domain:** `diskominfo.sanggau.go.id`
6. **Ubah values:**
   ```
   upload_max_filesize = 32M
   post_max_size = 64M
   memory_limit = 512M
   max_execution_time = 300
   ```
7. **Save Changes**

#### **Option B: Via .htaccess** (Jika tidak ada MultiPHP INI Editor)

1. **File Manager → `/public_html/public/.htaccess`**
2. **Edit file**
3. **Tambahkan di bagian ATAS** (sebelum `<IfModule mod_rewrite.c>`):
   ```apache
   # PHP Upload Limits
   php_value upload_max_filesize 32M
   php_value post_max_size 64M
   php_value memory_limit 512M
   php_value max_execution_time 300
   ```
4. **Save Changes**

---

### **STEP 3: Upload Controller yang Sudah Difix**

Controller sudah diupdate dengan:
- ✅ Auto-create folder jika belum ada
- ✅ Error handling lebih baik
- ✅ Validasi file lebih ketat
- ✅ Error message lebih jelas

**Upload file ini ke cPanel:**

1. **File di PC:** `C:\xampp\htdocs\sanggau-backend\app\Http\Controllers\Api\Admin\BannerController.php`

2. **Upload ke:** `/home/diskominfo/public_html/app/Http/Controllers/Api/Admin/BannerController.php`

3. **Cara:**
   - cPanel File Manager
   - Navigate ke: `app/Http/Controllers/Api/Admin/`
   - Backup file lama: Copy `BannerController.php` → Rename jadi `BannerController.php.backup`
   - Delete `BannerController.php` lama
   - Upload file baru dari PC
   - Verify file timestamp hari ini

---

### **STEP 4: Clear Cache Laravel** (Optional tapi Recommended)

Jika ada akses Terminal/SSH:
```bash
cd /home/diskominfo/public_html
php artisan config:clear
php artisan cache:clear
```

**Jika TIDAK ada Terminal**, hapus cache manual:
1. File Manager → `/public_html/bootstrap/cache/`
2. Delete semua file `.php` di folder ini (KECUALI `.gitignore`)
3. File Manager → `/public_html/storage/framework/cache/data/`
4. Delete semua file/folder di dalamnya

---

## 🧪 TESTING

### **TEST 1: Upload Banner Baru**

1. **Login CMS sebagai Admin**
2. **Menu Banner → + Tambah Banner**
3. **Isi form:**
   - Judul: `Test Banner Upload`
   - Sub Judul: `Testing`
   - Upload Gambar: Pilih gambar (max 5MB)
4. **Klik Save**

**Expected:**
- ✅ **Berhasil disimpan** (tidak ada error)
- ✅ **Gambar muncul di list**

**Jika masih error:**
- Screenshot error message nya
- Buka browser F12 → Console tab → Screenshot error
- Buka F12 → Network tab → Klik request yang error → Screenshot response

---

### **TEST 2: Edit Banner Existing**

1. **Menu Banner → Edit banner existing**
2. **Upload gambar baru**
3. **Save**

**Expected:**
- ✅ Berhasil update
- ✅ Gambar lama terganti dengan gambar baru

---

### **TEST 3: Verify Gambar Tersimpan di Server**

1. **File Manager → `/public_html/public/uploads/banner/`**
2. **Harus ada file gambar** dengan nama seperti: `1234567890_abcdefghij.jpg`
3. **File size** harus > 0 KB (tidak 0 bytes)

---

### **TEST 4: Verify Gambar Muncul di Frontend**

1. **Buka homepage:** `https://diskominfo.sanggau.go.id`
2. **Banner slider harus muncul** dengan gambar yang baru diupload
3. **Klik kanan gambar → "Open image in new tab"**
4. **URL harus:** `https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg`

---

## 🚨 TROUBLESHOOTING

### **Problem: Masih error "gambar failed to upload"**

**Check 1: Folder permissions**
- `/public_html/public/uploads/` → 0775
- `/public_html/public/uploads/banner/` → 0775

**Check 2: Disk space**
- cPanel → Disk Usage
- Pastikan masih ada space (tidak full)

**Check 3: PHP limits**
- Verify di MultiPHP INI Editor values sudah update
- Atau verify .htaccess sudah ada php_value settings

**Check 4: File size**
- Gambar yang diupload harus < 5MB
- Compress gambar jika terlalu besar

---

### **Problem: Upload berhasil, tapi gambar tidak muncul di frontend**

**Check 1: Verify file exists**
- File Manager → `/public_html/public/uploads/banner/`
- File gambar ada?

**Check 2: Verify URL gambar**
- Akses: `https://diskominfo.sanggau.go.id/uploads/banner/namafile.jpg`
- Harus bisa dibuka

**Check 3: Verify Model Banner sudah terupdate**
- File `app/Models/Banner.php` di server harus versi yang sudah difix
- Check accessor `getGambarAttribute()` harus ada

---

### **Problem: Error "mkdir(): Permission denied"**

Folder `uploads/` atau `public/` tidak writable.

**Solusi:**
1. Set permissions folder `public/` → 0755
2. Set permissions folder `public/uploads/` → 0775
3. Atau hubungi hosting provider untuk fix permissions

---

## 📋 CHECKLIST FIX BANNER UPLOAD

### Folder & Permissions:
- [ ] ✅ Folder `/public/uploads/` exists
- [ ] ✅ Folder `/public/uploads/banner/` exists
- [ ] ✅ Permissions `/public/uploads/` → 0775
- [ ] ✅ Permissions `/public/uploads/banner/` → 0775

### PHP Limits:
- [ ] ✅ upload_max_filesize → 32M
- [ ] ✅ post_max_size → 64M
- [ ] ✅ memory_limit → 512M

### Controller Updated:
- [ ] ✅ BannerController.php terupdate (check timestamp)
- [ ] ✅ Controller punya auto-create folder logic
- [ ] ✅ Controller punya error handling

### Testing:
- [ ] ✅ Upload banner baru berhasil
- [ ] ✅ Edit banner existing berhasil
- [ ] ✅ Gambar tersimpan di `/uploads/banner/`
- [ ] ✅ Gambar muncul di frontend
- [ ] ✅ URL gambar full path (https://...)

---

## ⏱️ ESTIMASI WAKTU

```
Fix folder permissions:      5 menit
Update PHP limits:           3 menit
Upload controller:           3 menit
Clear cache:                 2 menit
Testing upload:              5 menit
────────────────────────────────────
TOTAL:                       18 menit
```

---

## 🎉 SELESAI!

Setelah semua fix:
- ✅ Upload banner berhasil
- ✅ Gambar tersimpan di server
- ✅ Gambar muncul di frontend
- ✅ Error handling lebih baik

---

**MULAI DARI STEP 1: Fix folder permissions dulu!**

Itu yang paling penting dan paling sering jadi masalah!

**Good Luck! 🚀**
