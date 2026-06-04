@extends('layouts.app')

@section('title', 'Pengumuman - ' . config('app.name'))

@section('content')
{{-- Hero Section --}}
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Pengumuman</span>
        </div>
        <h1>Pengumuman</h1>
        <p>Informasi resmi dan pengumuman terbaru dari Diskominfo Sanggau</p>
    </div>
</div>

{{-- Content Section --}}
<section class="section">
    <div class="container">
        {{-- Filter Buttons --}}
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.75rem;">
            <button onclick="filterPengumuman('semua')" id="filter-semua" 
                class="filter-btn active" 
                style="padding: 0.5rem 1.25rem; border-radius: 999px; border: 1.5px solid; cursor: pointer; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;">
                📋 Semua
            </button>
            <button onclick="filterPengumuman('penting')" id="filter-penting" 
                class="filter-btn" 
                style="padding: 0.5rem 1.25rem; border-radius: 999px; border: 1.5px solid; cursor: pointer; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;">
                ⚠️ Penting
            </button>
        </div>

        {{-- Pengumuman List --}}
        @if($pengumuman->isEmpty())
            <div style="text-align: center; padding: 5rem; color: var(--text-muted);">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📢</div>
                <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">
                    Tidak ada pengumuman
                </div>
                <div style="font-size: 0.9rem;">
                    Belum ada pengumuman yang tersedia saat ini
                </div>
            </div>
        @else
            <div id="pengumuman-list" style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($pengumuman as $item)
                    <div class="pengumuman-item" data-penting="{{ $item->penting ? 'true' : 'false' }}" 
                        onclick="togglePengumuman({{ $item->id }})" 
                        style="background: var(--bg-surface); border-radius: 16px; border: 2px solid {{ $item->penting ? '#FCA5A5' : 'var(--border)' }}; padding: 1.5rem; cursor: pointer; transition: all 0.2s;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap; align-items: center;">
                                    <span class="badge {{ $item->penting ? 'badge-red' : 'badge-blue' }}" style="font-size: 0.75rem;">
                                        {{ $item->penting ? '⚠️ Penting' : 'ℹ️ Info' }}
                                    </span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">
                                        📅 {{ \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') }}
                                    </span>
                                </div>
                                <h3 style="font-size: 1rem; font-weight: 700; line-height: 1.4; color: var(--text-primary);">
                                    {{ $item->judul }}
                                </h3>
                                
                                <div id="content-{{ $item->id }}" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); color: var(--text-secondary); line-height: 1.8; font-size: 0.9rem; white-space: pre-wrap;">
                                    {{ $item->konten }}
                                    @if($item->tanggal_selesai)
                                        <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 8px; font-size: 0.85rem; color: #92400E; font-weight: 600;">
                                            ⏰ Berlaku hingga: {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <button id="icon-{{ $item->id }}" 
                                style="flex-shrink: 0; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #F1F5F9; color: #94A3B8; border: none; cursor: pointer; transition: all 0.3s; font-size: 1.2rem;">
                                ▾
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pagination --}}
        @if($pengumuman->hasPages())
            <div style="margin-top: 3rem;">
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>
</section>

<style>
.filter-btn {
    border-color: var(--border);
    background: white;
    color: var(--text-secondary);
}
.filter-btn.active {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
}
.filter-btn:hover:not(.active) {
    border-color: var(--primary-light);
    background: var(--primary-soft);
}
</style>

<script>
function togglePengumuman(id) {
    const content = document.getElementById('content-' + id);
    const icon = document.getElementById('icon-' + id);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
        icon.style.background = '#DBEAFE';
        icon.style.color = 'var(--primary)';
    } else {
        content.style.display = 'none';
        icon.style.transform = 'rotate(0)';
        icon.style.background = '#F1F5F9';
        icon.style.color = '#94A3B8';
    }
}

function filterPengumuman(type) {
    const items = document.querySelectorAll('.pengumuman-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => btn.classList.remove('active'));
    document.getElementById('filter-' + type).classList.add('active');
    
    // Filter items
    items.forEach(item => {
        if (type === 'semua') {
            item.style.display = 'block';
        } else if (type === 'penting') {
            item.style.display = item.dataset.penting === 'true' ? 'block' : 'none';
        }
    });
}
</script>
@endsection
