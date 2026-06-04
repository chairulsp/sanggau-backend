@extends('layouts.app')

@section('title', 'Layanan Digital - ' . config('app.name'))

@section('content')
{{-- Hero Section --}}
<div style="position: relative; background: linear-gradient(160deg, #0A2540 0%, #1B4332 50%, #0A2540 100%); padding: 5rem 0 4rem; overflow: hidden;">
    {{-- Decorative SVG --}}
    <svg style="position: absolute; bottom: 0; left: 0; right: 0; opacity: 0.1;" viewBox="0 0 1440 120" preserveAspectRatio="none">
        <path d="M0,120 L0,70 Q200,20 400,55 Q600,90 800,35 Q1000,-20 1200,25 Q1350,55 1440,40 L1440,120 Z" fill="#4ADE80"/>
    </svg>
    
    <div class="container" style="position: relative; z-index: 2; text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.4rem; margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.55); font-size: 0.82rem; text-decoration: none;">Beranda</a>
            <span style="color: rgba(255,255,255,0.3); font-size: 0.75rem;">/</span>
            <span style="color: white; font-size: 0.82rem; font-weight: 600;">Layanan</span>
        </div>
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.25); border-radius: 999px; font-size: 0.72rem; font-weight: 700; color: #86EFAC; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 1.25rem;">
            💻 E-Government
        </div>
        <h1 style="font-size: clamp(2rem,4vw,3rem); font-weight: 900; color: white; letter-spacing: -0.02em; margin-bottom: 1rem; line-height: 1.1;">
            Layanan Digital Terintegrasi
        </h1>
        <p style="font-size: 1.05rem; color: rgba(255,255,255,0.72); max-width: 560px; margin: 0 auto;">
            Semua layanan pemerintahan dalam satu portal yang mudah diakses oleh masyarakat Kabupaten Sanggau
        </p>
    </div>
</div>

{{-- Content Section --}}
<section style="background: linear-gradient(160deg, #0A2540 0%, #1B4332 50%, #0A2540 100%); padding: 3rem 0 6rem; position: relative;">
    <div class="container" style="position: relative; z-index: 2;">
        
        {{-- Filter Kategori --}}
        @if($kategoriList->count() > 1)
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2.5rem; justify-content: center;">
                <button onclick="filterLayanan('Semua')" id="filter-Semua" class="kategori-btn active" data-kategori="Semua">
                    Semua
                </button>
                @foreach($kategoriList as $kat)
                    @if($kat != 'Semua')
                        <button onclick="filterLayanan('{{ $kat }}')" id="filter-{{ Str::slug($kat) }}" class="kategori-btn" data-kategori="{{ $kat }}">
                            {{ $kat }}
                        </button>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Counter --}}
        <div style="text-align: center; margin-bottom: 2rem; color: rgba(255,255,255,0.45); font-size: 0.82rem;">
            Menampilkan <strong style="color: #86EFAC;" id="layanan-count">{{ $layanan->count() }}</strong> layanan
        </div>

        {{-- Grid Layanan --}}
        @if($layanan->isEmpty())
            <div style="text-align: center; padding: 5rem; color: rgba(255,255,255,0.5);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💻</div>
                <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Belum ada layanan</div>
                <div style="font-size: 0.9rem;">Layanan digital akan segera tersedia</div>
            </div>
        @else
            <div id="layanan-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.25rem;">
                @foreach($layanan as $item)
                    <a href="{{ $item->link ?? '#' }}" target="_blank" rel="noopener noreferrer"
                        class="layanan-card" data-kategori="{{ $item->kategori }}"
                        style="padding: 1.75rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; text-decoration: none; background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; transition: all 0.3s ease; position: relative; overflow: hidden;">
                        
                        {{-- Glow Corner --}}
                        <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: radial-gradient(circle, rgba(59,130,246,0.35), transparent 70%); pointer-events: none;"></div>
                        
                        {{-- Icon --}}
                        <div style="width: 56px; height: 56px; border-radius: 16px; background: {{ $item->warna ?? 'linear-gradient(135deg, #1D4ED8, #3B82F6)' }}; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(59,130,246,0.35); flex-shrink: 0;">
                            @if($item->ikon && Str::startsWith($item->ikon, ['http://', 'https://']))
                                <img src="{{ $item->ikon }}" style="width: 30px; height: 30px; object-fit: contain;" alt="{{ $item->nama }}">
                            @else
                                <span style="font-size: 26px; color: white;">💻</span>
                            @endif
                        </div>
                        
                        {{-- Kategori Badge --}}
                        <div style="position: absolute; top: 1rem; right: 1rem;">
                            <span style="font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.08); padding: 0.2rem 0.5rem; border-radius: 5px; text-transform: uppercase; letter-spacing: 0.05em;">
                                {{ $item->kategori }}
                            </span>
                        </div>
                        
                        {{-- Content --}}
                        <div style="flex: 1;">
                            <div style="font-size: 1rem; font-weight: 700; color: white; margin-bottom: 0.5rem; line-height: 1.3;">
                                {{ $item->nama }}
                            </div>
                            <div style="font-size: 0.82rem; color: rgba(255,255,255,0.6); line-height: 1.65;">
                                {{ $item->deskripsi }}
                            </div>
                        </div>
                        
                        {{-- Footer --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.875rem; border-top: 1px solid rgba(255,255,255,0.08);">
                            <span style="font-size: 0.72rem; color: #86EFAC; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">Akses Layanan</span>
                            <div style="width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;">
                                <span style="color: white;">→</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
.kategori-btn {
    padding: 0.45rem 1.1rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.7);
}
.kategori-btn.active {
    background: #10B981;
    color: white;
    border-color: #10B981;
    box-shadow: 0 4px 16px rgba(16,185,129,0.35);
}
.kategori-btn:hover:not(.active) {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.25);
}
.layanan-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(59,130,246,0.35);
    border-color: rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.1);
}
</style>

<script>
function filterLayanan(kategori) {
    // Update button states
    document.querySelectorAll('.kategori-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const targetId = 'filter-' + (kategori === 'Semua' ? 'Semua' : kategori.toLowerCase().replace(/\s+/g, '-'));
    const targetBtn = document.getElementById(targetId);
    if (targetBtn) targetBtn.classList.add('active');
    
    // Filter cards
    const cards = document.querySelectorAll('.layanan-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardKategori = card.dataset.kategori;
        if (kategori === 'Semua' || cardKategori === kategori) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update counter
    const counter = document.getElementById('layanan-count');
    if (counter) counter.textContent = visibleCount;
}
</script>
@endsection
