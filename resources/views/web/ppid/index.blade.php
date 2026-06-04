@extends('layouts.app')

@section('title', 'Portal PPID - ' . config('app.name'))

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">PPID</span>
        </div>
        <h1>Portal PPID</h1>
        <p>Pejabat Pengelola Informasi dan Dokumentasi Diskominfo Sanggau</p>
    </div>
</div>

<section class="section">
    <div class="container">
        {{-- Intro --}}
        <div style="background: var(--bg-surface); border-radius: 20px; border: 1px solid var(--border); padding: 2rem; margin-bottom: 2.5rem; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
            @foreach([
                ['icon' => '📋', 'title' => 'Informasi Publik', 'desc' => 'Daftar informasi yang wajib disediakan dan diumumkan secara berkala'],
                ['icon' => '📝', 'title' => 'Permohonan Informasi', 'desc' => 'Ajukan permohonan informasi publik secara tertulis'],
                ['icon' => '📊', 'title' => 'Laporan PPID', 'desc' => 'Laporan tahunan pengelolaan informasi publik']
            ] as $item)
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">{{ $item['icon'] }}</div>
                    <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">{{ $item['title'] }}</h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.6;">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Tabs --}}
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.75rem; flex-wrap: wrap;">
            @foreach(['Wajib Tersedia', 'Berkala', 'Serta Merta', 'Dikecualikan'] as $index => $kat)
                <button onclick="filterPpid('{{ $kat }}')" id="filter-{{ Str::slug($kat) }}" 
                    class="ppid-filter {{ $index === 0 ? 'active' : '' }}" 
                    style="padding: 0.6rem 1.25rem; border-radius: 999px; border: 1.5px solid; cursor: pointer; font-weight: 600; font-size: 0.85rem; font-family: inherit; transition: all 0.2s;">
                    {{ $kat }}
                </button>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 48px;">No.</th>
                        <th>Nama Informasi</th>
                        <th>Deskripsi</th>
                        <th style="width: 90px;">Tahun</th>
                        <th style="width: 120px;">Unduh</th>
                    </tr>
                </thead>
                <tbody id="ppid-tbody">
                    @forelse($ppid as $index => $item)
                        <tr class="ppid-row" data-kategori="{{ $item->kategori }}">
                            <td style="font-weight: 700; color: #94A3B8;">{{ $index + 1 }}</td>
                            <td style="font-weight: 600;">{{ $item->judul }}</td>
                            <td style="color: #6B7280;">{{ $item->deskripsi ?? '-' }}</td>
                            <td>{{ $item->tahun ?? '-' }}</td>
                            <td>
                                @if($item->file_url && $item->file_url !== '#')
                                    <a href="{{ $item->file_url }}" target="_blank" class="btn btn-primary btn-sm">⬇️ Unduh</a>
                                @else
                                    <span style="color: #94A3B8; font-size: 0.82rem;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2.5rem; color: #94A3B8;">Belum ada data PPID.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<style>
.ppid-filter {
    border-color: var(--border);
    background: white;
    color: var(--text-secondary);
}
.ppid-filter.active {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
}
.ppid-filter:hover:not(.active) {
    border-color: var(--primary-light);
    background: var(--primary-soft);
}
</style>

<script>
function filterPpid(kategori) {
    // Update buttons
    document.querySelectorAll('.ppid-filter').forEach(btn => btn.classList.remove('active'));
    document.getElementById('filter-' + kategori.toLowerCase().replace(/\s+/g, '-')).classList.add('active');
    
    // Filter rows
    const rows = document.querySelectorAll('.ppid-row');
    rows.forEach(row => {
        const rowKat = row.dataset.kategori;
        row.style.display = rowKat === kategori ? 'table-row' : 'none';
    });
}

// Set initial filter
filterPpid('Wajib Tersedia');
</script>
@endsection
