@extends('layouts.app')

@section('title', 'Pengaduan Masyarakat - ' . config('app.name'))

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Pengaduan</span>
        </div>
        <h1>Pengaduan Masyarakat</h1>
        <p>Sampaikan aspirasi dan pengaduan Anda kepada kami</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem; align-items: start;">
            {{-- Form --}}
            <div style="background: var(--bg-surface); border-radius: 20px; border: 1px solid var(--border); padding: 2.5rem; box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem; box-shadow: 0 8px 24px rgba(26,86,219,0.3);">
                        📝
                    </div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">Form Pengaduan</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Isi form di bawah ini dengan lengkap dan jelas</p>
                </div>

                <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color: #EF4444;">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Email <span style="color: #EF4444;">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color: #EF4444;">*</span></label>
                        <select name="kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Layanan">Layanan Publik</option>
                            <option value="Infrastruktur">Infrastruktur TI</option>
                            <option value="Website">Website & Aplikasi</option>
                            <option value="Informasi">Informasi & Data</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Pengaduan <span style="color: #EF4444;">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Ringkasan singkat pengaduan" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Isi Pengaduan <span style="color: #EF4444;">*</span></label>
                        <textarea name="isi" class="form-control" rows="6" placeholder="Jelaskan pengaduan Anda dengan detail..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran (opsional)</label>
                        <input type="file" name="lampiran" class="form-control" accept="image/*,.pdf">
                        <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">
                            Format: JPG, PNG, PDF. Maks 2MB
                        </small>
                    </div>

                    @if(session('success'))
                        <div style="background: #D1FAE5; border: 1px solid #34D399; color: #065F46; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem;">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background: #FEE2E2; border: 1px solid #F87171; color: #991B1B; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem;">
                            ⚠️ Terdapat kesalahan pada form. Silakan periksa kembali.
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 1rem;">
                        📤 Kirim Pengaduan
                    </button>
                </form>
            </div>

            {{-- Info --}}
            <div>
                {{-- Panduan --}}
                <div style="background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border-radius: 20px; border: 1px solid #93C5FD; padding: 2rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #1E40AF; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        💡 Panduan Pengaduan
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0; color: #1E3A8A; font-size: 0.9rem; line-height: 1.8;">
                        <li style="padding-left: 1.5rem; position: relative; margin-bottom: 0.75rem;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Isi form dengan data yang valid
                        </li>
                        <li style="padding-left: 1.5rem; position: relative; margin-bottom: 0.75rem;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Jelaskan masalah secara detail dan jelas
                        </li>
                        <li style="padding-left: 1.5rem; position: relative; margin-bottom: 0.75rem;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Lampirkan bukti jika diperlukan
                        </li>
                        <li style="padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Tunggu respon dari petugas kami
                        </li>
                    </ul>
                </div>

                {{-- Status --}}
                <div style="background: var(--bg-surface); border-radius: 20px; border: 1px solid var(--border); padding: 2rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">📊 Statistik Pengaduan</h3>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #FEF3C7; border-radius: 10px;">
                            <span style="font-size: 0.85rem; font-weight: 600;">⏳ Menunggu</span>
                            <span style="font-size: 1.1rem; font-weight: 800; color: #92400E;">-</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #DBEAFE; border-radius: 10px;">
                            <span style="font-size: 0.85rem; font-weight: 600;">🔄 Diproses</span>
                            <span style="font-size: 1.1rem; font-weight: 800; color: #1E40AF;">-</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #D1FAE5; border-radius: 10px;">
                            <span style="font-size: 0.85rem; font-weight: 600;">✅ Selesai</span>
                            <span style="font-size: 1.1rem; font-weight: 800; color: #065F46;">-</span>
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div style="background: var(--bg-surface); border-radius: 20px; border: 1px solid var(--border); padding: 2rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">📞 Kontak Pengaduan</h3>
                    <div style="font-size: 0.9rem; line-height: 1.8; color: var(--text-secondary);">
                        <div style="margin-bottom: 0.75rem;">
                            <strong>Email:</strong><br>
                            pengaduan@sanggau.go.id
                        </div>
                        <div style="margin-bottom: 0.75rem;">
                            <strong>Telepon:</strong><br>
                            (0564) 21234
                        </div>
                        <div>
                            <strong>Jam Pelayanan:</strong><br>
                            Senin - Jumat: 07.30 - 16.00 WIB
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
