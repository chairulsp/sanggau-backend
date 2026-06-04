@extends('layouts.app')

@section('title', 'Beranda - Diskominfo Kabupaten Sanggau')
@section('meta_description', 'Portal Resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau. Menuju Sanggau yang Informatif & Digital.')

@push('styles')
<style>
    /* Hero Slider */
    .hero-section { position: relative; min-height: 92vh; overflow: hidden; }
    .hero-slide { position: absolute; inset: 0; transition: opacity 0.9s cubic-bezier(.4,0,.2,1); }
    .hero-slide-img { position: absolute; inset: 0; background-size: cover; background-position: center; }
    .hero-overlay { position: absolute; inset: 0; background: linear-gradient(125deg, rgba(7,14,55,.93) 0%, rgba(26,86,219,.72) 55%, rgba(14,165,233,.45) 100%); }
    .hero-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .75rem 1.75rem; border-radius: 10px; font-weight: 700; font-size: .92rem; text-decoration: none; transition: all .25s ease; }
    .hero-btn-primary { background: #F59E0B; color: #1C1917; }
    .hero-btn-primary:hover { background: #FBBF24; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(245,158,11,.4); }
    .hero-btn-ghost { background: rgba(255,255,255,.15); color: white; border: 1px solid rgba(255,255,255,.35); backdrop-filter: blur(8px); }
    .hero-btn-ghost:hover { background: rgba(255,255,255,.25); transform: translateY(-2px); }
    
    /* Card Layanan */
    .layanan-card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; transition: all .25s; text-decoration: none; display: block; }
    .layanan-card:hover { box-shadow: 0 12px 32px rgba(0,0,0,.08); transform: translateY(-3px); border-color: #1A56DB; }
    .layanan-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 1rem; }
    
    /* Berita Card */
    .berita-card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; transition: all .25s; text-decoration: none; display: block; }
    .berita-card:hover { box-shadow: 0 12px 32px rgba(0,0,0,.08); transform: translateY(-3px); }
    .berita-thumb { position: relative; overflow: hidden; aspect-ratio: 16/10; background: #EEF2FF; }
    .berita-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
    .berita-card:hover .berita-thumb img { transform: scale(1.06); }
    
    @media (max-width: 768px) {
        .hero-section { min-height: 70vh; }
        .grid-3, .grid-4 { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .grid-3, .grid-4 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- Hero Slider Section --}}
<section class="hero-section">
    @if($banners->count() > 0)
        @foreach($banners as $index => $banner)
        <div class="hero-slide" style="z-index: {{ $index === 0 ? 2 : 1 }}; opacity: {{ $index === 0 ? 1 : 0 }};" data-slide="{{ $index }}">
            <div class="hero-slide-img" style="background-image: url('{{ Storage::url($banner->gambar) }}');"></div>
            <div class="hero-overlay"></div>
            <div class="container" style="position: relative; z-index: 2; padding: 6rem 1.5rem; min-height: 92vh; display: flex; align-items: center;">
                <div style="max-width: 600px;">
                    <div style="display: inline-flex; align-items: center; gap: .5rem; padding: .4rem 1rem .4rem .5rem; border-radius: 999px; background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.3); backdrop-filter: blur(8px); margin-bottom: 1.5rem;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(245,158,11,.25); display: flex; align-items: center; justify-content: center;">
                            🏛️
                        </div>
                        <span style="font-size: .72rem; font-weight: 700; color: #FCD34D; letter-spacing: .06em; text-transform: uppercase;">Diskominfo Kabupaten Sanggau</span>
                    </div>
                    <h1 style="font-size: clamp(2rem,3.8vw,3.4rem); font-weight: 900; color: white; line-height: 1.08; margin-bottom: 1.25rem; letter-spacing: -.02em;">
                        {{ $banner->judul }}
                    </h1>
                    <p style="color: rgba(255,255,255,.78); font-size: 1.05rem; line-height: 1.75; margin-bottom: 2rem;">
                        {{ $banner->subjudul }}
                    </p>
                    <div style="display: flex; gap: .875rem; flex-wrap: wrap;">
                        <a href="{{ route('layanan') }}" class="hero-btn hero-btn-primary">
                            🔧 Layanan Digital
                        </a>
                        <a href="{{ route('berita') }}" class="hero-btn hero-btn-ghost">
                            📰 Baca Berita
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        {{-- Default Hero --}}
        <div class="hero-slide" style="z-index: 2; opacity: 1;">
            <div class="hero-slide-img" style="background: linear-gradient(135deg, #070E37, #1A56DB);"></div>
            <div class="container" style="position: relative; z-index: 2; padding: 6rem 1.5rem; min-height: 92vh; display: flex; align-items: center;">
                <div style="max-width: 600px;">
                    <h1 style="font-size: clamp(2rem,3.8vw,3.4rem); font-weight: 900; color: white; line-height: 1.08; margin-bottom: 1.25rem;">
                        Menuju Sanggau yang <span style="color: #60A5FA;">Informatif & Digital</span>
                    </h1>
                    <p style="color: rgba(255,255,255,.78); font-size: 1.05rem; line-height: 1.75; margin-bottom: 2rem;">
                        Dinas Komunikasi dan Informatika Kabupaten Sanggau hadir untuk memberikan layanan informasi dan komunikasi terbaik bagi masyarakat.
                    </p>
                    <div style="display: flex; gap: .875rem; flex-wrap: wrap;">
                        <a href="{{ route('layanan') }}" class="hero-btn hero-btn-primary">🔧 Layanan Digital</a>
                        <a href="{{ route('profil') }}" class="hero-btn hero-btn-ghost">📄 Profil Kami</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

{{-- Layanan Section --}}
@if($layanan->count() > 0)
<section class="section" style="background: #F8FAFF;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: clamp(1.6rem, 3vw, 2rem); font-weight: 900; margin-bottom: .75rem;">Layanan Digital Kami</h2>
            <p style="color: var(--text-muted); font-size: 1.05rem;">Berbagai layanan digital untuk kemudahan masyarakat</p>
        </div>
        
        <div class="grid-4" style="gap: 1.25rem;">
            @foreach($layanan->take(8) as $lay)
            <a href="{{ $lay->url }}" target="{{ $lay->url_target ?? '_blank' }}" class="layanan-card">
                <div class="layanan-icon" style="background: linear-gradient(135deg, #1A56DB, #3B82F6); color: white;">
                    @if($lay->icon)
                        {!! $lay->icon !!}
                    @else
                        🔧
                    @endif
                </div>
                <h3 style="font-size: .95rem; font-weight: 700; margin-bottom: .5rem; color: var(--text-primary);">{{ $lay->judul }}</h3>
                <p style="font-size: .82rem; color: var(--text-muted); line-height: 1.6;">{{ Str::limit($lay->deskripsi, 80) }}</p>
            </a>
            @endforeach
        </div>
        
        @if($layanan->count() > 8)
        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="{{ route('layanan') }}" style="display: inline-flex; align-items: center; gap: .5rem; padding: .75rem 1.75rem; background: #1A56DB; color: white; border-radius: 10px; font-weight: 700; text-decoration: none; transition: all .25s;">
                Lihat Semua Layanan →
            </a>
        </div>
        @endif
    </div>
</section>
@endif

{{-- Berita Terbaru Section --}}
@if($berita->count() > 0)
<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: clamp(1.6rem, 3vw, 2rem); font-weight: 900; margin-bottom: .5rem;">Berita Terbaru</h2>
                <p style="color: var(--text-muted); font-size: 1rem;">Informasi dan berita terkini dari Diskominfo Sanggau</p>
            </div>
            <a href="{{ route('berita') }}" style="color: #1A56DB; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: .3rem;">
                Lihat Semua →
            </a>
        </div>
        
        <div class="grid-3" style="gap: 1.25rem;">
            @foreach($berita->take(6) as $news)
            <a href="{{ route('berita.show', $news->slug) }}" class="berita-card">
                <div class="berita-thumb">
                    @if($news->gambar)
                    <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80'">
                    @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #EEF2FF, #DBEAFE); font-size: 3rem;">
                        📰
                    </div>
                    @endif
                    <div style="position: absolute; top: .75rem; left: .75rem;">
                        <span style="background: rgba(26,86,219,.9); color: white; padding: .25rem .75rem; border-radius: 999px; font-size: .7rem; font-weight: 700;">
                            {{ $news->kategori ?? 'Berita' }}
                        </span>
                    </div>
                </div>
                <div style="padding: 1.25rem;">
                    <h3 style="font-size: .95rem; font-weight: 700; margin-bottom: .5rem; line-height: 1.4; color: var(--text-primary);">
                        {{ Str::limit($news->judul, 60) }}
                    </h3>
                    <p style="font-size: .82rem; color: var(--text-muted); line-height: 1.6; margin-bottom: .75rem;">
                        {{ Str::limit($news->ringkasan, 100) }}
                    </p>
                    <div style="display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: var(--text-muted);">
                        <span>✍️ {{ $news->penulis ?? 'Admin' }}</span>
                        <span>·</span>
                        <span>📅 {{ $news->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Statistik Section --}}
@if($statistik->count() > 0)
<section class="section" style="background: linear-gradient(135deg, #0F2061, #1A56DB); color: white;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: clamp(1.6rem, 3vw, 2rem); font-weight: 900; margin-bottom: .75rem;">Statistik Kabupaten Sanggau</h2>
            <p style="color: rgba(255,255,255,.75); font-size: 1.05rem;">Data dan informasi penting Kabupaten Sanggau</p>
        </div>
        
        <div class="grid-4" style="gap: 1.5rem;">
            @foreach($statistik as $stat)
            <div style="background: rgba(255,255,255,.1); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,.2); border-radius: 16px; padding: 2rem 1.5rem; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: .5rem;">{{ $stat->ikon ?? '📊' }}</div>
                <div style="font-size: 2rem; font-weight: 900; margin-bottom: .5rem;">{{ $stat->nilai }}</div>
                <div style="font-size: .9rem; color: rgba(255,255,255,.75);">{{ $stat->nama }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Pengumuman & Agenda Section --}}
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            {{-- Pengumuman --}}
            @if($pengumuman->count() > 0)
            <div>
                <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .5rem;">
                    📢 Pengumuman
                </h3>
                <div style="display: flex; flex-direction: column; gap: .75rem;">
                    @foreach($pengumuman->take(5) as $peng)
                    <a href="{{ route('pengumuman') }}" style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: 12px; padding: 1rem 1.25rem; text-decoration: none; transition: all .2s;">
                        <div style="display: flex; align-items: flex-start; gap: .75rem;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #F59E0B, #FBBF24); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                                {{ $peng->penting ? '⚠️' : '📢' }}
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: .88rem; font-weight: 700; color: var(--text-primary); margin-bottom: .25rem;">
                                    {{ Str::limit($peng->judul, 80) }}
                                </h4>
                                <span style="font-size: .72rem; color: var(--text-muted);">
                                    {{ \Carbon\Carbon::parse($peng->tanggal_mulai)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Agenda --}}
            @if($agenda->count() > 0)
            <div>
                <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .5rem;">
                    📅 Agenda
                </h3>
                <div style="display: flex; flex-direction: column; gap: .75rem;">
                    @foreach($agenda->take(5) as $agd)
                    <a href="{{ route('agenda') }}" style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: 12px; padding: 1rem 1.25rem; text-decoration: none; transition: all .2s;">
                        <div style="display: flex; align-items: flex-start; gap: .75rem;">
                            <div style="width: 48px; text-align: center; flex-shrink: 0;">
                                <div style="font-size: 1.4rem; font-weight: 900; color: #1A56DB; line-height: 1;">
                                    {{ \Carbon\Carbon::parse($agd->tanggal_mulai)->format('d') }}
                                </div>
                                <div style="font-size: .65rem; color: var(--text-muted); text-transform: uppercase;">
                                    {{ \Carbon\Carbon::parse($agd->tanggal_mulai)->format('M') }}
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: .88rem; font-weight: 700; color: var(--text-primary); margin-bottom: .25rem;">
                                    {{ Str::limit($agd->judul, 70) }}
                                </h4>
                                <div style="font-size: .72rem; color: var(--text-muted); display: flex; align-items: center; gap: .3rem;">
                                    📍 {{ $agd->lokasi ?? 'TBA' }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Simple hero slider
    const slides = document.querySelectorAll('.hero-slide');
    let currentSlide = 0;
    
    if (slides.length > 1) {
        setInterval(() => {
            slides[currentSlide].style.opacity = '0';
            slides[currentSlide].style.zIndex = '1';
            
            currentSlide = (currentSlide + 1) % slides.length;
            
            slides[currentSlide].style.opacity = '1';
            slides[currentSlide].style.zIndex = '2';
        }, 5500);
    }
</script>
@endpush
