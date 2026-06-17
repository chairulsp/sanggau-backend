# 📦 CARA COMPRESS FOLDER VENDOR

## Vendor Sudah Di-generate! Sekarang Tinggal Compress & Upload

---

## ✅ STATUS

- ✅ Vendor sudah di-generate dengan `composer install --no-dev`
- ✅ Autoload sudah di-optimize dengan `composer dump-autoload -o`
- ✅ Helpers sudah included dalam autoload
- ✅ Production-ready (dev dependencies sudah dihapus)

**Lokasi vendor:** `C:\xampp\htdocs\sanggau-backend\vendor\`

---

## 📦 CARA COMPRESS VENDOR (Windows)

### METHOD 1: Windows Built-in ZIP (Paling Mudah)

1. **Buka Windows Explorer**
2. **Navigate ke:** `C:\xampp\htdocs\sanggau-backend\`
3. **Klik kanan folder `vendor`**
4. **Pilih:** `Send to` → `Compressed (zipped) folder`
5. **Tunggu sampai selesai** (2-5 menit)
6. **Akan muncul file:** `vendor.zip` di folder yang sama

**Ukuran Expected:** ~20-40 MB (compressed)

---

### METHOD 2: 7-Zip (Compression Lebih Bagus)

Jika ada 7-Zip installed:

1. **Download 7-Zip** (jika belum ada): https://www.7-zip.org/
2. **Install 7-Zip**
3. **Klik kanan folder `vendor`**
4. **Pilih:** `7-Zip` → `Add to archive...`
5. **Settings:**
   - Archive format: `zip`
   - Compression level: `Normal` (atau `Ultra` jika mau lebih kecil)
   - Archive name: `vendor.zip`
6. **Klik OK**
7. **Tunggu sampai selesai**

**Ukuran Expected:**
- Normal: ~25-35 MB
- Ultra: ~15-25 MB (tapi lebih lama compress-nya)

---

### METHOD 3: WinRAR

Jika ada WinRAR:

1. **Klik kanan folder `vendor`**
2. **Pilih:** `Add to "vendor.zip"`
3. **Tunggu selesai**

---

## 📤 SETELAH COMPRESS

**File yang dihasilkan:**
```
C:\xampp\htdocs\sanggau-backend\vendor.zip
```

**Ukuran file:** ~20-40 MB (tergantung compression method)

**Next Step:**
1. ✅ Upload `vendor.zip` ke cPanel File Manager
2. ✅ Extract di cPanel
3. ✅ Delete vendor.zip (cleanup)

---

## 🚀 UPLOAD KE cPanel

Setelah compress selesai, ikuti panduan ini:

**📖 Buka:** [UPLOAD-TANPA-TERMINAL.md](./UPLOAD-TANPA-TERMINAL.md)

**Langsung ke STEP 3:** "Upload vendor.zip & Extract"

---

## ⚠️ TIPS

1. **Jangan compress ke .rar atau .7z** - cPanel File Manager biasanya hanya support .zip
2. **Check ukuran file** - jika terlalu besar (>100MB), gunakan compression level lebih tinggi
3. **Jika upload gagal** karena file terlalu besar:
   - Coba compress dengan level Ultra
   - Atau split vendor jadi beberapa bagian
   - Atau upload via FTP (jika ada akses)

---

## 🧪 VERIFY SEBELUM UPLOAD

**Check file vendor.zip:**
- ✅ File size: 20-40 MB (masuk akal)
- ✅ File type: ZIP compressed folder
- ✅ Bisa dibuka dengan double-click (test extract di local)

---

## ✅ READY!

**vendor.zip sudah siap untuk diupload ke cPanel!**

**Next:** Ikuti [UPLOAD-TANPA-TERMINAL.md](./UPLOAD-TANPA-TERMINAL.md)

---

**Good Luck! 🚀**
