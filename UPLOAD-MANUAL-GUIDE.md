# 📤 PANDUAN UPLOAD MANUAL ke cPanel File Manager

## Cara Upload & Replace File via File Manager cPanel

**Tidak perlu SSH, tidak perlu FTP, langsung via browser!**

---

## 🎯 PERSIAPAN

### File yang Perlu Di-upload (Replace):

```
✅ WAJIB UPLOAD:
📁 app/Models/Berita.php
📁 app/Models/Banner.php
📁 app/Models/Galeri.php
📁 app/Helpers/helpers.php        ← Folder baru!
📁 routes/api.php
📁 composer.json

✅ OPSIONAL (jika ingin update .env):
📁 .env.production                 ← Rename jadi .env di server

❌ JANGAN UPLOAD:
   - vendor/ (nanti di-generate via composer)
   - node_modules/
   - .git/
   - storage/logs/*
```

---

## 📋 LANGKAH-LANGKAH UPLOAD

### **STEP 1: Login cPanel**

1. Buka browser, akses: **https://sanggau.go.id:2083/**
   (atau https://diskominfo.sanggau.go.id:2083/)

2. Login dengan credentials cPanel Anda:
   ```
   Username: _____________
   Password: _____________
   ```

3. Cari menu **"File Manager"** (biasanya di section "Files")
   
4. Klik **File Manager**

---

### **STEP 2: Masuk ke Directory Website**

1. File Manager akan terbuka di tab baru

2. Navigasi ke folder website Anda:
   ```
   Biasanya di:
   - /public_html
   atau
   - /home/username/public_html
   atau
   - /domains/diskominfo.sanggau.go.id/public_html
   ```

3. Pastikan Anda melihat folder-folder Laravel:
   ```
   ✓ app/
   ✓ bootstrap/
   ✓ config/
   ✓ database/
   ✓ public/
   ✓ routes/
   ✓ storage/
   ✓ vendor/
   ✓ .env
   ✓ composer.json
   ```

---

### **STEP 3: Upload File - app/Models/**

#### A. Berita.php

1. **Klik folder `app/`** di File Manager
2. **Klik folder `Models/`**
3. Cari file **`Berita.php`**
4. **BACKUP DULU:**
   - Klik kanan pada `Berita.php`
   - Pilih **"Copy"**
   - Paste dengan nama **`Berita.php.backup`**
5. **DELETE file lama:**
   - Klik kanan `Berita.php`
   - Pilih **"Delete"**
   - Confirm
6. **UPLOAD file baru:**
   - Klik tombol **"Upload"** di toolbar atas
   - Klik **"Select File"**
   - Browse ke: `C:\xampp\htdocs\sanggau-backend\app\Models\Berita.php`
   - Pilih file, klik **Open**
   - File akan auto-upload
   - Klik **"Go Back"** untuk kembali ke File Manager

#### B. Banner.php

**ULANGI LANGKAH YANG SAMA:**
1. Backup: `Banner.php` → `Banner.php.backup`
2. Delete: `Banner.php`
3. Upload: `C:\xampp\htdocs\sanggau-backend\app\Models\Banner.php`

#### C. Galeri.php

**ULANGI LANGKAH YANG SAMA:**
1. Backup: `Galeri.php` → `Galeri.php.backup`
2. Delete: `Galeri.php`
3. Upload: `C:\xampp\htdocs\sanggau-backend\app\Models\Galeri.php`

---

### **STEP 4: Upload Folder Baru - app/Helpers/**

**⚠️ PENTING: Folder Helpers belum ada, harus dibuat!**

1. **Masih di folder `app/`**
2. Klik tombol **"+ Folder"** di toolbar
3. Ketik nama folder: **`Helpers`**
4. Klik **"Create New Folder"**
5. **Masuk ke folder `Helpers/`** (double click)
6. **Upload helpers.php:**
   - Klik **"Upload"**
   - Select file: `C:\xampp\htdocs\sanggau-backend\app\Helpers\helpers.php`
   - Upload
   - Klik **"Go Back"**

---

### **STEP 5: Upload File - routes/api.php**

1. **Klik "Go to home directory"** atau navigate ke root folder website
2. **Klik folder `routes/`**
3. **BACKUP:**
   - Klik kanan `api.php`
   - Copy → `api.php.backup`
4. **DELETE:**
   - Delete `api.php`
5. **UPLOAD:**
   - Upload file: `C:\xampp\htdocs\sanggau-backend\routes\api.php`

---

### **STEP 6: Upload File - composer.json**

1. **Kembali ke root folder** (public_html)
2. **BACKUP:**
   - Copy `composer.json` → `composer.json.backup`
3. **DELETE:**
   - Delete `composer.json`
4. **UPLOAD:**
   - Upload file: `C:\xampp\htdocs\sanggau-backend\composer.json`

---

### **STEP 7: Update .env (OPSIONAL tapi PENTING)**

#### Cara 1: Upload .env.production

1. **BACKUP .env lama:**
   - Copy `.env` → `.env.backup`
2. **DELETE .env lama:**
   - Delete `.env`
3. **UPLOAD .env.production:**
   - Upload: `C:\xampp\htdocs\sanggau-backend\.env.production`
4. **RENAME:**
   - Klik kanan file `

.env.production` yang baru di-upload
   - Pilih **"Rename"**
   - Ganti nama jadi: **`.env`**
5. **EDIT .env:**
   - Klik kanan `.env`
   - Pilih **"Edit"**
   - Update:
     ```env
     APP_URL=https://diskominfo.sanggau.go.id
     DB_HOST=localhost
     DB_DATABASE=your_database_name
     DB_USERNAME=your_db_username
     DB_PASSWORD=your_db_password
     ```
   - Klik **"Save Changes"**

#### Cara 2: Edit .env langsung (Lebih Mudah)

1. **Klik kanan `.env`** yang sudah ada
2. Pilih **"Edit"**
3. **Cari dan update baris ini:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://diskominfo.sanggau.go.id
   ```
4. Klik **"Save Changes"**

---

### **STEP 8: Verify Upload**

**Pastikan semua file terupload:**

```
✓ app/Models/Berita.php         (file size ~2-3 KB)
✓ app/Models/Banner.php         (file size ~2-3 KB)
✓ app/Models/Galeri.php         (file size ~2-3 KB)
✓ app/Helpers/helpers.php       (file size ~1-2 KB) ← NEW
✓ routes/api.php                (file size ~5-7 KB)
✓ composer.json                 (file size ~2-3 KB)
✓ .env                          (updated)
```

**Cek file size & timestamp:**
- File size harus masuk akal (tidak 0 KB)
- Timestamp harus baru (hari ini)

---

## 🔧 STEP 9: Run Commands via Terminal cPanel

**Setelah upload, WAJIB run commands ini:**

### A. Buka Terminal cPanel

1. Kembali ke **cPanel Dashboard**
2. Cari menu **"Terminal"** (di section "Advanced")
3. Klik **Terminal**
4. Terminal akan terbuka

### B. Masuk ke Directory

```bash
cd public_html
# atau
cd domains/diskominfo.sanggau.go.id/public_html
```

Verify dengan:
```bash
pwd
# Output harus: /home/username/public_html
```

### C. Run Commands (COPY & PASTE SATU PER SATU)

**1. Install Dependencies:**
```bash
composer install --no-dev --optimize-autoloader
```
⏳ Tunggu sampai selesai (1-3 menit)

**2. Regenerate Autoload:**
```bash
composer dump-autoload -o
```
⏳ Tunggu selesai

**3. Clear Config Cache:**
```bash
php artisan config:clear
```

**4. Clear Application Cache:**
```bash
php artisan cache:clear
```

**5. Set Permissions Upload Folder:**
```bash
chmod -R 777 public/uploads
```

**6. DONE! ✅**

---

## 🧪 STEP 10: Testing

### Test via Terminal cPanel:

```bash
# Test API banner
curl https://diskominfo.sanggau.go.id/api/banner

# Harus return JSON dengan full URL:
# "gambar": "https://diskominfo.sanggau.go.id/uploads/banner/xxx.jpg"
```

### Test via Browser:

1. **Buka:** https://diskominfo.sanggau.go.id/api/banner
2. **Verify:** Field `gambar` harus full URL (bukan `/uploads/...`)

3. **Buka:** https://diskominfo.sanggau.go.id/api/berita
4. **Verify:** Field `gambar` harus full URL

---

## 📸 SCREENSHOT PANDUAN

### File Manager cPanel Layout:

```
┌─────────────────────────────────────────────┐
│  [< Back]  [Upload]  [+Folder]  [Delete]    │ ← Toolbar
├─────────────────────────────────────────────┤
│  📁 app/                                     │
│  📁 bootstrap/                               │
│  📁 config/                                  │
│  📁 database/                                │
│  📁 public/                                  │
│  📁 routes/                                  │
│  📁 storage/                                 │
│  📁 vendor/                                  │
│  📄 .env                                     │
│  📄 composer.json                            │
└─────────────────────────────────────────────┘
```

### Klik Kanan Menu:

```
┌──────────────────┐
│ View             │
│ Edit             │
│ Copy             │
│ Move             │
│ Rename           │
│ Delete           │
│ Compress         │
│ Extract          │
│ Permissions      │
└──────────────────┘
```

---

## ⚠️ TROUBLESHOOTING

### Upload File Gagal (Error)

**Problem:** "Upload failed" atau file size 0 KB

**Solusi:**
1. Check file size limit di cPanel (biasanya 50MB max)
2. Compress file jadi .zip, upload .zip, lalu extract
3. Atau gunakan "Code Editor" untuk copy-paste kode

### File Tidak Ter-replace

**Problem:** Setelah upload, file masih versi lama

**Solusi:**
1. **Pastikan DELETE file lama** sebelum upload
2. **Clear browser cache** (Ctrl+Shift+Del)
3. **Verify di terminal:**
   ```bash
   cat app/Models/Berita.php | head -20
   # Harus ada comment "Accessor untuk gambar"
   ```

### Composer Error

**Problem:** `composer: command not found`

**Solusi:**
```bash
# Try dengan PHP path:
php /usr/local/bin/composer install --no-dev --optimize-autoloader

# Atau gunakan alias:
/usr/local/bin/composer install --no-dev --optimize-autoloader
```

### Permission Denied

**Problem:** Cannot create directory atau write file

**Solusi:**
```bash
# Set owner
chown -R username:username .

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs public/uploads
```

---

## 🎯 QUICK CHECKLIST

Sudah upload semua?

- [ ] ✅ app/Models/Berita.php (backup dulu, delete, upload baru)
- [ ] ✅ app/Models/Banner.php (backup dulu, delete, upload baru)
- [ ] ✅ app/Models/Galeri.php (backup dulu, delete, upload baru)
- [ ] ✅ app/Helpers/helpers.php (buat folder Helpers, upload file)
- [ ] ✅ routes/api.php (backup dulu, delete, upload baru)
- [ ] ✅ composer.json (backup dulu, delete, upload baru)
- [ ] ✅ .env (edit atau replace, update APP_URL & DB credentials)
- [ ] ✅ Run: composer install
- [ ] ✅ Run: composer dump-autoload -o
- [ ] ✅ Run: php artisan config:clear
- [ ] ✅ Run: php artisan cache:clear
- [ ] ✅ Run: chmod -R 777 public/uploads
- [ ] ✅ Test: curl API banner (harus return full URL)
- [ ] ✅ Test: Buka API di browser
- [ ] ✅ Test: Upload gambar di CMS
- [ ] ✅ Test: Gambar muncul di frontend

---

## 💾 BACKUP FILES

**File backup yang dibuat:**
- `app/Models/Berita.php.backup`
- `app/Models/Banner.php.backup`
- `app/Models/Galeri.php.backup`
- `routes/api.php.backup`
- `composer.json.backup`
- `.env.backup`

**Jika ada masalah, restore dengan:**
1. Delete file baru
2. Copy `.backup` file
3. Rename, hapus `.backup`

---

## 📞 BANTUAN

Stuck? Check:
1. File size setelah upload (tidak boleh 0 KB)
2. Timestamp file (harus hari ini)
3. Terminal output untuk error messages
4. Laravel logs: `storage/logs/laravel.log`

---

## ✅ SELESAI!

Jika semua checklist ✅, backend sudah updated dan siap!

**Next:** Deploy frontend via Git (lihat [GIT-COMMIT-GUIDE.md](./sanggau-frontend/GIT-COMMIT-GUIDE.md))

---

**Happy Uploading! 🚀**
