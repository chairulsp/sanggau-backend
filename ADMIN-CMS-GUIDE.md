# 🎛️ PANDUAN ADMIN CMS - DISKOMINFO SANGGAU

Panduan lengkap menggunakan Content Management System (CMS) Admin Panel untuk mengelola website Diskominfo Kabupaten Sanggau.

---

## 📋 DAFTAR ISI

1. [Pendahuluan](#pendahuluan)
2. [Akses Admin Panel](#akses-admin-panel)
3. [Fitur yang Tersedia](#fitur-yang-tersedia)
4. [Manajemen Berita](#manajemen-berita)
5. [Tips & Best Practices](#tips--best-practices)
6. [Troubleshooting](#troubleshooting)

---

## 🎯 PENDAHULUAN

### Apa itu Admin CMS?

Admin CMS (Content Management System) adalah panel administrasi berbasis web yang memungkinkan Anda untuk:
- ✅ Mengelola konten website tanpa coding
- ✅ Menambah, edit, dan hapus berita
- ✅ Mengatur galeri foto dan video
- ✅ Mengelola pengaduan masyarakat
- ✅ Monitoring statistik pengunjung
- ✅ Dan banyak lagi!

### Teknologi

- **Backend**: Laravel 8 (PHP Framework)
- **Database**: MySQL
- **Frontend Admin**: Blade Templates + Custom CSS
- **Authentication**: Laravel Authentication
- **File Upload**: Laravel Storage

---

## 🔐 AKSES ADMIN PANEL

### URL Login

```
Development: http://127.0.0.1:8000/admin/login
Production: https://diskominfo-sanggau.go.id/admin/login
```

### Kredensial Default

**⚠️ PENTING**: Segera ganti password setelah login pertama kali!

```
Email: admin@diskominfo-sanggau.go.id
Password: (hubungi administrator)
```

### Cara Login

1. Buka browser (Chrome, Firefox, Edge, Safari)
2. Akses URL admin panel
3. Masukkan **Email** dan **Password**
4. Centang "Ingat Saya" jika menggunakan komputer pribadi
5. Klik tombol **"Masuk"**

### Halaman Setelah Login

Setelah berhasil login, Anda akan diarahkan ke **Dashboard** yang menampilkan:
- 📊 Statistik konten (berita, galeri, pengaduan, dll)
- 👥 Jumlah pengunjung
- 📰 Berita terbaru
- 💬 Pengaduan terbaru
- 🔐 Riwayat login

---

## 🛠️ FITUR YANG TERSEDIA

### 1. **Dashboard** 📊
- Statistik lengkap website
- Quick access ke semua fitur
- Monitoring pengunjung real-time
- Riwayat login pengguna

### 2. **Manajemen Berita** 📰
- Tambah berita baru
- Edit berita existing
- Hapus berita
- Filter berdasarkan kategori & status
- Search berita
- Toggle status Published/Draft
- Upload gambar berita

### 3. **Galeri** 🎬
*(Coming Soon - Dalam Pengembangan)*
- Upload foto
- Upload video YouTube
- Manage album

### 4. **Banner** 🎯
*(Coming Soon)*
- Kelola banner slider homepage
- Set urutan tampil
- Schedule banner

### 5. **Layanan** ⚙️
*(Coming Soon)*
- Tambah layanan publik
- Kategorisasi layanan

### 6. **Agenda** 📅
*(Coming Soon)*
- Buat agenda kegiatan
- Kalender event

### 7. **Pengumuman** 📢
*(Coming Soon)*
- Publikasi pengumuman
- Set prioritas penting

### 8. **PPID** 📄
*(Coming Soon)*
- Kelola informasi publik
- Upload dokumen PPID

### 9. **Dokumen Download** 📥
*(Coming Soon)*
- Upload dokumen
- Kategorisasi file

### 10. **Pengaduan** 💬
*(Coming Soon)*
- Monitor pengaduan masuk
- Update status pengaduan
- Balas pengaduan

### 11. **Profil Diskominfo** 🏛️
*(Coming Soon)*
- Update visi misi
- Edit struktur organisasi
- Manage data pegawai

### 12. **Pengguna** 👥
*(Coming Soon)*
- Tambah admin baru
- Edit hak akses
- Manage role & permission

### 13. **Pengaturan** ⚙️
*(Coming Soon)*
- Site settings
- SEO configuration
- Social media links

---

## 📰 MANAJEMEN BERITA

### A. Melihat Daftar Berita

1. Klik menu **"Berita"** di sidebar
2. Anda akan melihat tabel dengan kolom:
   - **Gambar**: Thumbnail berita
   - **Judul**: Judul berita (klik untuk detail)
   - **Kategori**: Badge kategori
   - **Penulis**: Nama penulis
   - **Status**: Published (hijau) atau Draft (kuning)
   - **Tanggal**: Waktu publish
   - **Aksi**: Tombol Edit & Hapus

### B. Filter & Search Berita

**Filter berdasarkan**:
- 🔍 **Search**: Cari berdasarkan judul, konten, atau kategori
- 📁 **Kategori**: Filter kategori spesifik
- ✅ **Status**: Published atau Draft

**Cara menggunakan filter**:
1. Isi form filter di atas tabel
2. Klik tombol **"🔍 Filter"**
3. Klik **"🔄 Reset"** untuk reset filter

### C. Menambah Berita Baru

1. Klik tombol **"➕ Tambah Berita"** di kanan atas
2. Isi formulir dengan lengkap:

**Field yang wajib diisi** (bertanda *):
- **Judul Berita**: Judul yang menarik dan informatif
- **Kategori**: Pilih dari dropdown atau ketik baru
- **Konten Berita**: Isi berita lengkap
- **Penulis**: Nama penulis (default: nama Anda)

**Field opsional**:
- **Ringkasan**: Summary singkat (max 500 karakter)
- **Gambar Utama**: Upload gambar ilustrasi
  - Format: JPG, PNG, GIF
  - Max size: 5MB
  - Rekomendasi: 1200x630 pixels

**Status Publikasi**:
- ✅ **Centang**: Langsung publish
- ⬜ **Tidak dicentang**: Simpan sebagai draft

3. Klik **"💾 Simpan Berita"**
4. Sistem akan redirect ke daftar berita dengan notifikasi sukses

### D. Mengedit Berita

1. Di daftar berita, klik tombol **"✏️"** (Edit) pada berita yang ingin diubah
2. Form edit akan tampil dengan data existing
3. Ubah data yang diperlukan
4. Untuk **mengganti gambar**:
   - Gambar lama akan tetap ditampilkan
   - Upload gambar baru jika ingin ganti
   - Jika tidak upload, gambar lama tetap digunakan
5. Klik **"💾 Update Berita"**

### E. Mengubah Status Berita (Published/Draft)

**Cara Cepat** (tanpa masuk form edit):
1. Di daftar berita, klik badge **Status** (warna hijau/kuning)
2. Konfirmasi perubahan
3. Status akan berubah otomatis
4. Badge berubah warna (hijau = Published, kuning = Draft)

**Catatan**: 
- Berita Published akan tampil di website publik
- Berita Draft hanya bisa dilihat di admin panel

### F. Menghapus Berita

1. Di daftar berita, klik tombol **"🗑️"** (Hapus)
2. Akan muncul konfirmasi: **"Yakin ingin menghapus berita ini?"**
3. Klik **"OK"** untuk konfirmasi
4. Berita akan dihapus permanent dari database

**⚠️ PERINGATAN**: 
- Penghapusan bersifat **PERMANENT**
- Data tidak bisa dikembalikan
- Gambar berita akan terhapus dari server

### G. Preview Berita

1. Di daftar berita, klik tombol **"👁️"** (Lihat)
2. Akan membuka halaman berita di tab baru
3. Anda bisa melihat tampilan berita seperti yang dilihat pengunjung

---

## 💡 TIPS & BEST PRACTICES

### Tips Menulis Berita yang Baik

1. **Judul**:
   - Maksimal 100 karakter
   - Jelas dan informatif
   - Hindari clickbait berlebihan
   - Gunakan huruf besar di awal kata penting

2. **Kategori**:
   - Gunakan kategori yang konsisten
   - Jangan terlalu banyak kategori
   - Rekomendasi kategori:
     - Teknologi
     - Pendidikan
     - Kesehatan
     - Ekonomi
     - Sosial & Budaya
     - Infrastruktur
     - Pariwisata

3. **Ringkasan**:
   - 100-200 karakter ideal
   - Berisi poin utama berita
   - Menarik minat pembaca
   - Bagus untuk SEO

4. **Konten**:
   - Gunakan paragraf pendek (3-4 kalimat)
   - Struktur: Intro → Body → Closing
   - Gunakan heading jika perlu
   - Masukkan data & fakta akurat

5. **Gambar**:
   - Ukuran rekomendasi: 1200x630px (rasio 1.91:1)
   - Gambar tajam dan berkualitas
   - Relevan dengan isi berita
   - Hindari gambar copyrighted

6. **SEO**:
   - Gunakan keyword di judul
   - Isi ringkasan dengan baik
   - Kategori membantu SEO
   - Alt text gambar otomatis dari judul

### Workflow Publikasi Berita

```
Draft → Review → Edit → Finalisasi → Publish
```

**Proses Ideal**:
1. Tulis berita dan simpan sebagai **Draft**
2. Review ulang: cek typo, grammar, fakta
3. Minta rekan untuk review (optional)
4. Edit jika diperlukan
5. Ubah status ke **Published**

### Keamanan

- 🔐 **Selalu logout** setelah selesai
- 🔑 **Jangan share password** ke orang lain
- 🖥️ **Gunakan komputer aman** (bukan komputer umum)
- 🔄 **Ganti password** secara berkala
- 📱 **Aktifkan 2FA** jika tersedia

---

## 🔧 TROUBLESHOOTING

### Masalah Umum & Solusi

#### 1. Tidak Bisa Login

**Penyebab**:
- Email atau password salah
- Account tidak aktif
- Session expired

**Solusi**:
- Pastikan email dan password benar
- Coba reset password
- Clear browser cache
- Hubungi administrator

#### 2. Gambar Tidak Muncul

**Penyebab**:
- File terlalu besar (>5MB)
- Format tidak didukung
- Storage link belum dibuat

**Solusi**:
```bash
# Di server, jalankan:
php artisan storage:link
```

#### 3. Error 500 Saat Upload

**Penyebab**:
- File size melebihi PHP limit
- Permission folder storage

**Solusi**:
```bash
# Set permission folder storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Atau di Windows (as Admin):
icacls storage /grant Users:F /t
```

#### 4. Berita Published Tidak Muncul di Website

**Penyebab**:
- Cache belum di-clear
- Status masih draft

**Solusi**:
- Pastikan status Published (hijau)
- Clear cache browser
- Refresh halaman berita publik

#### 5. Tabel Berita Kosong

**Penyebab**:
- Database belum ada data
- Filter terlalu ketat

**Solusi**:
- Klik "Reset" di filter
- Tambah berita baru
- Check database connection

---

## 📞 DUKUNGAN

### Butuh Bantuan?

**IT Support Diskominfo Sanggau**:
- 📧 Email: it@diskominfo-sanggau.go.id
- 📱 WhatsApp: +62 812-XXXX-XXXX
- 🏢 Kantor: Jl. [Alamat Diskominfo]

### Jam Operasional Support

```
Senin - Jumat: 08:00 - 16:00 WIB
Sabtu - Minggu: Tutup
```

---

## 📚 REFERENSI

### URL Penting

| Nama | URL | Keterangan |
|------|-----|------------|
| Website Publik | https://diskominfo-sanggau.go.id | Website utama |
| Admin Login | /admin/login | Panel admin CMS |
| Dashboard | /admin/dashboard | Dashboard admin |
| Berita | /admin/berita | Manajemen berita |

### Dokumentasi Tambahan

- `README-LARAVEL-BLADE.md` - Dokumentasi teknis project
- `QUICK-REFERENCE.md` - Referensi cepat command
- `STATUS-FINAL.md` - Status lengkap fitur

---

## 🔄 UPDATE LOG

### Version 1.0 (3 Juni 2026)
- ✅ Sistem login & authentication
- ✅ Dashboard dengan statistik
- ✅ CRUD Berita lengkap
- ✅ Upload gambar berita
- ✅ Filter & search berita
- ✅ Toggle status Published/Draft

### Upcoming Features (v1.1)
- ⏳ Manajemen Galeri
- ⏳ Manajemen Banner
- ⏳ Manajemen Layanan
- ⏳ Manajemen Pengaduan
- ⏳ Rich Text Editor (WYSIWYG)

---

**Prepared by**: Kiro AI Assistant  
**Version**: 1.0  
**Last Updated**: 3 Juni 2026  
**For**: Dinas Komunikasi dan Informatika Kabupaten Sanggau

---

© 2026 Diskominfo Kabupaten Sanggau. All rights reserved.
