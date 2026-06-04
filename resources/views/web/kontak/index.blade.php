@extends('layouts.app')

@section('title', 'Hubungi Kami - ' . config('app.name'))

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Kontak</span>
        </div>
        <h1>Hubungi Kami</h1>
        <p>Diskominfo Kabupaten Sanggau siap melayani Anda</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            {{-- Info --}}
            <div>
                <div class="section-label" style="margin-bottom: 1rem;">📍 Informasi Kantor</div>
                <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2;">
                    Dinas Komunikasi dan Informatika Kabupaten Sanggau
                </h2>

                @php
                    $contacts = [
                        ['icon' => '📍', 'title' => 'Alamat', 'content' => 'Jl. Jenderal Sudirman No. 3, Sanggau, Kalimantan Barat 78511'],
                        ['icon' => '📞', 'title' => 'Telepon', 'content' => '(0564) 21234'],
                        ['icon' => '📠', 'title' => 'Fax', 'content' => '(0564) 21235'],
                        ['icon' => '✉️', 'title' => 'Email', 'content' => 'diskominfo@sanggau.go.id'],
                        ['icon' => '🌐', 'title' => 'Website', 'content' => 'https://diskominfo.sanggaukab.go.id'],
                        ['icon' => '⏰', 'title' => 'Jam Kerja', 'content' => 'Senin – Jumat: 07.30 – 16.00 WIB'],
                    ];
                @endphp

                @foreach($contacts as $item)
                    <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem; padding: 1.1rem; border-radius: 12px; background: var(--bg-surface); border: 1px solid var(--border);">
                        <div style="width: 42px; height: 42px; border-radius: 10px; background: var(--primary-soft); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            {{ $item['icon'] }}
                        </div>
                        <div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem;">{{ $item['title'] }}</div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $item['content'] }}</div>
                        </div>
                    </div>
                @endforeach

                {{-- Media Sosial --}}
                <div style="padding: 1.25rem; border-radius: 12px; background: var(--primary-soft); border: 1px solid var(--border); margin-top: 0.5rem;">
                    <div style="font-weight: 700; color: var(--primary); margin-bottom: 0.75rem; font-size: 0.875rem;">📱 Ikuti Kami di Media Sosial</div>
                    <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
                        @foreach([
                            ['label' => '🔵 Facebook', 'url' => 'https://facebook.com/diskominfoSanggau'],
                            ['label' => '📷 Instagram', 'url' => 'https://instagram.com/diskominfo_sanggau'],
                            ['label' => '▶ YouTube', 'url' => 'https://youtube.com/@diskominfoSanggau']
                        ] as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background: var(--bg-surface); color: var(--primary); border: 1px solid var(--border);">
                                {{ $social['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Maps + Form --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                {{-- Google Maps --}}
                <div style="border-radius: 16px; overflow: hidden; border: 1px solid var(--border); height: 280px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.7!2d110.5!3d0.13!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMCIwJ04gMTEwwqAzMCdF!5e0!3m2!1sid!2sid!4v1" width="100%" height="280" style="border: none;" allowfullscreen loading="lazy" title="Lokasi Diskominfo Sanggau"></iframe>
                </div>

                {{-- Info Pengaduan --}}
                <div style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 2rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">💬 Pengaduan Masyarakat</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">
                        Untuk pengaduan resmi, silakan gunakan halaman Pengaduan Masyarakat.
                    </p>
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        📨 Buka Halaman Pengaduan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
