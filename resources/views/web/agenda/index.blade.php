@extends('layouts.app')

@section('title', 'Agenda Kegiatan - ' . config('app.name'))

@section('content')
{{-- Hero Section --}}
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Agenda</span>
        </div>
        <h1>Agenda Kegiatan</h1>
        <p>Jadwal kegiatan dan acara resmi Diskominfo Kabupaten Sanggau</p>
    </div>
</div>

{{-- Content Section --}}
<section class="section">
    <div class="container">
        {{-- Filter Buttons --}}
        <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem;">
            <button onclick="filterAgenda('akan-datang')" id="filter-akan-datang" 
                class="filter-btn active" 
                style="padding: 0.6rem 1.5rem; border-radius: 999px; border: 1.5px solid; cursor: pointer; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;">
                📅 Akan Datang
            </button>
            <button onclick="filterAgenda('semua')" id="filter-semua" 
                class="filter-btn" 
                style="padding: 0.6rem 1.5rem; border-radius: 999px; border: 1.5px solid; cursor: pointer; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;">
                🗂️ Semua Agenda
            </button>
        </div>

        {{-- Agenda List --}}
        @if($agenda->isEmpty())
            <div style="text-align: center; padding: 5rem; color: var(--text-muted);">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📅</div>
                <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">
                    Tidak ada agenda
                </div>
                <div style="font-size: 0.9rem;">
                    Belum ada kegiatan terjadwal saat ini.
                </div>
            </div>
        @else
            <div id="agenda-list" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach($agenda as $item)
                    @php
                        $isPast = \Carbon\Carbon::parse($item->tanggal_mulai)->isPast();
                        $tanggalMulai = \Carbon\Carbon::parse($item->tanggal_mulai);
                    @endphp
                    <div class="agenda-item" data-status="{{ $isPast ? 'past' : 'upcoming' }}" 
                        style="background: var(--bg-surface); border-radius: 16px; border: 1px solid {{ $isPast ? 'var(--border)' : 'var(--border-strong)' }}; overflow: hidden; display: grid; grid-template-columns: 100px 1fr; opacity: {{ $isPast ? '0.7' : '1' }}; transition: all 0.2s;">
                        
                        {{-- Date Box --}}
                        <div style="background: {{ $isPast ? '#94A3B8' : 'linear-gradient(135deg, var(--primary-dark), var(--primary))' }}; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem 1rem;">
                            <div style="color: rgba(255,255,255,0.7); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">
                                {{ $tanggalMulai->locale('id')->format('M') }}
                            </div>
                            <div style="color: white; font-size: 2.5rem; font-weight: 800; line-height: 1;">
                                {{ $tanggalMulai->format('d') }}
                            </div>
                            <div style="color: rgba(255,255,255,0.7); font-size: 0.72rem;">
                                {{ $tanggalMulai->format('Y') }}
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div style="padding: 1.5rem;">
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.6rem; flex-wrap: wrap; align-items: center;">
                                @if($isPast)
                                    <span style="font-size: 0.7rem; background: #F1F5F9; color: #64748B; padding: 0.2rem 0.6rem; border-radius: 999px; font-weight: 700;">Selesai</span>
                                @else
                                    <span style="font-size: 0.7rem; background: var(--primary-soft); color: var(--primary); padding: 0.2rem 0.6rem; border-radius: 999px; font-weight: 700;">● Akan Datang</span>
                                @endif
                            </div>
                            <h3 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4;">
                                {{ $item->judul }}
                            </h3>
                            @if($item->deskripsi)
                                <p style="color: var(--text-muted); font-size: 0.875rem; line-height: 1.6; margin-bottom: 0.75rem;">
                                    {{ Str::limit($item->deskripsi, 150) }}
                                </p>
                            @endif
                            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                                <span style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.35rem;">
                                    📅 {{ $tanggalMulai->locale('id')->isoFormat('D MMMM YYYY') }}
                                    @if($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai)
                                        — {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
                                    @endif
                                </span>
                                @if($item->lokasi)
                                    <span style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.35rem;">
                                        📍 {{ $item->lokasi }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pagination --}}
        @if($agenda->hasPages())
            <div style="margin-top: 3rem;">
                {{ $agenda->links() }}
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
.agenda-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}
.agenda-item[data-status="past"]:hover {
    transform: none;
    box-shadow: none;
}
</style>

<script>
function filterAgenda(type) {
    const items = document.querySelectorAll('.agenda-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => btn.classList.remove('active'));
    document.getElementById('filter-' + type).classList.add('active');
    
    // Filter items
    items.forEach(item => {
        if (type === 'semua') {
            item.style.display = 'grid';
        } else if (type === 'akan-datang') {
            item.style.display = item.dataset.status === 'upcoming' ? 'grid' : 'none';
        }
    });
}
</script>
@endsection
