@extends('layouts.app')

@section('title', 'Download Dokumen - ' . config('app.name'))

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Download Dokumen</span>
        </div>
        <h1>Download Dokumen</h1>
        <p>Unduh dokumen resmi dan publikasi Diskominfo Kabupaten Sanggau</p>
    </div>
</div>

<section class="section">
    <div class="container">
        {{-- Search & Filter --}}
        <div style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <input type="text" id="search-input" placeholder="🔍 Cari dokumen..." 
                style="flex: 1 1 220px; padding: 0.6rem 1rem; border-radius: 10px; border: 1.5px solid var(--border); font-family: inherit; font-size: 0.875rem; outline: none;"
                onkeyup="filterDokumen()">
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                @foreach(['Semua', 'Perencanaan', 'Laporan', 'Kebijakan', 'Peraturan', 'SOP', 'Lainnya'] as $index => $kat)
                    <button onclick="setKategori('{{ $kat }}')" id="kat-{{ Str::slug($kat) }}" 
                        class="kat-btn {{ $index === 0 ? 'active' : '' }}"
                        style="padding: 0.4rem 1rem; border-radius: 999px; border: 1.5px solid; cursor: pointer; font-weight: 600; font-size: 0.78rem; font-family: inherit; transition: all 0.2s;">
                        {{ $kat }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Counter --}}
        <div style="margin-bottom: 1.25rem; color: var(--text-muted); font-size: 0.875rem;">
            Menampilkan <strong style="color: var(--primary);" id="doc-count">{{ $dokumen->count() }}</strong> dokumen
        </div>

        {{-- Dokumen List --}}
        @if($dokumen->isEmpty())
            <div style="text-align: center; padding: 5rem; color: var(--text-muted);">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📂</div>
                <div style="font-weight: 700;">Tidak ada dokumen ditemukan</div>
            </div>
        @else
            <div id="dokumen-list" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @php
                    $iconMap = [
                        'Perencanaan' => '📊',
                        'Laporan' => '📋',
                        'Kebijakan' => '⚖️',
                        'Peraturan' => '📜',
                        'SOP' => '📝',
                        'Lainnya' => '📄',
                    ];
                @endphp

                @foreach($dokumen as $item)
                    <div class="dok-item" data-kategori="{{ $item->kategori }}" data-judul="{{ strtolower($item->judul) }}"
                        style="background: var(--bg-surface); border-radius: 14px; border: 1px solid var(--border); padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem; transition: all 0.2s;">
                        <div style="width: 52px; height: 52px; border-radius: 12px; background: var(--primary-soft); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                            {{ $iconMap[$item->kategori] ?? '📄' }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.25rem; line-height: 1.4;">{{ $item->judul }}</h3>
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <span style="font-size: 0.78rem; color: var(--primary); font-weight: 600;">{{ $item->kategori }}</span>
                                @if($item->tahun)
                                    <span style="font-size: 0.78rem; color: var(--text-muted);">📅 {{ $item->tahun }}</span>
                                @endif
                                @if($item->deskripsi)
                                    <span style="font-size: 0.78rem; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 300px;">{{ $item->deskripsi }}</span>
                                @endif
                            </div>
                        </div>
                        @if($item->file_url && $item->file_url !== '#')
                            <a href="{{ $item->file_url }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">⬇️ Unduh</a>
                        @else
                            <span style="padding: 0.4rem 1rem; background: var(--bg-muted); color: var(--text-muted); border-radius: 999px; font-size: 0.78rem; font-weight: 600;">Segera Hadir</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
.kat-btn {
    border-color: var(--border);
    background: white;
    color: var(--text-secondary);
}
.kat-btn.active {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
}
.kat-btn:hover:not(.active) {
    border-color: var(--primary-light);
    background: var(--primary-soft);
}
.dok-item:hover {
    border-color: var(--border-strong);
    box-shadow: var(--shadow-sm);
}
</style>

<script>
let currentKategori = 'Semua';

function setKategori(kat) {
    currentKategori = kat;
    document.querySelectorAll('.kat-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('kat-' + kat.toLowerCase().replace(/\s+/g, '-')).classList.add('active');
    filterDokumen();
}

function filterDokumen() {
    const search = document.getElementById('search-input').value.toLowerCase();
    const items = document.querySelectorAll('.dok-item');
    let visibleCount = 0;
    
    items.forEach(item => {
        const kategori = item.dataset.kategori;
        const judul = item.dataset.judul;
        const matchKat = currentKategori === 'Semua' || kategori === currentKategori;
        const matchSearch = !search || judul.includes(search);
        
        if (matchKat && matchSearch) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    document.getElementById('doc-count').textContent = visibleCount;
}
</script>
@endsection
