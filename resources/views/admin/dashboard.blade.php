@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-card.green {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }

    .stat-card.orange {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    }

    .stat-card.blue {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .list-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📰</div>
        <div class="stat-value">{{ $stats['berita'] }}</div>
        <div class="stat-label">Total Berita</div>
        <div style="margin-top: 10px; font-size: 0.875rem; opacity: 0.8;">
            {{ $stats['berita_published'] }} Published
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ $stats['visitors_today'] }}</div>
        <div class="stat-label">Pengunjung Hari Ini</div>
        <div style="margin-top: 10px; font-size: 0.875rem; opacity: 0.8;">
            {{ number_format($stats['visitors_total']) }} Total
        </div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">💬</div>
        <div class="stat-value">{{ $stats['pengaduan_pending'] }}</div>
        <div class="stat-label">Pengaduan Pending</div>
        <div style="margin-top: 10px; font-size: 0.875rem; opacity: 0.8;">
            {{ $stats['pengaduan'] }} Total
        </div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">📅</div>
        <div class="stat-value">{{ $stats['agenda_upcoming'] }}</div>
        <div class="stat-label">Agenda Mendatang</div>
        <div style="margin-top: 10px; font-size: 0.875rem; opacity: 0.8;">
            {{ $stats['agenda'] }} Total Agenda
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="card">
    <div class="card-header">
        <div class="card-title">📊 Statistik Konten</div>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $stats['galeri'] }}</div>
            <div style="font-size: 0.875rem; color: #718096;">📸 Galeri Foto</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $stats['layanan'] }}</div>
            <div style="font-size: 0.875rem; color: #718096;">⚙️ Layanan</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $stats['pengumuman'] }}</div>
            <div style="font-size: 0.875rem; color: #718096;">📢 Pengumuman</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $stats['dokumen'] }}</div>
            <div style="font-size: 0.875rem; color: #718096;">📥 Dokumen</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $stats['banner'] }}</div>
            <div style="font-size: 0.875rem; color: #718096;">🎯 Banner Aktif</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $stats['users'] }}</div>
            <div style="font-size: 0.875rem; color: #718096;">👥 Pengguna</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Latest Berita -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📰 Berita Terbaru</div>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-primary" style="font-size: 0.875rem; padding: 8px 15px;">
                Lihat Semua
            </a>
        </div>
        @forelse ($latestBerita as $berita)
            <div class="list-item">
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">{{ Str::limit($berita->judul, 50) }}</div>
                    <div style="font-size: 0.75rem; color: #718096;">
                        {{ $berita->created_at->diffForHumans() }} • {{ $berita->kategori }}
                    </div>
                </div>
                @if ($berita->aktif)
                    <span class="badge badge-success">Published</span>
                @else
                    <span class="badge badge-warning">Draft</span>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 20px; color: #718096;">
                Belum ada berita
            </div>
        @endforelse
    </div>

    <!-- Latest Pengaduan -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">💬 Pengaduan Terbaru</div>
            <a href="#" class="btn btn-primary" style="font-size: 0.875rem; padding: 8px 15px;">
                Lihat Semua
            </a>
        </div>
        @forelse ($latestPengaduan as $pengaduan)
            <div class="list-item">
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">{{ Str::limit($pengaduan->subjek, 40) }}</div>
                    <div style="font-size: 0.75rem; color: #718096;">
                        {{ $pengaduan->nama }} • {{ $pengaduan->created_at->diffForHumans() }}
                    </div>
                </div>
                @if ($pengaduan->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif ($pengaduan->status === 'diproses')
                    <span class="badge" style="background: #bee3f8; color: #2c5282;">Diproses</span>
                @else
                    <span class="badge badge-success">Selesai</span>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 20px; color: #718096;">
                Belum ada pengaduan
            </div>
        @endforelse
    </div>
</div>

<!-- Berita by Kategori -->
@if ($beritaByKategori->count() > 0)
<div class="card">
    <div class="card-header">
        <div class="card-title">📊 Berita per Kategori</div>
    </div>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        @foreach ($beritaByKategori as $item)
            <div style="flex: 1; min-width: 150px; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.75rem; font-weight: 700; margin-bottom: 5px;">{{ $item->total }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">{{ $item->kategori }}</div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Latest Login History -->
<div class="card">
    <div class="card-header">
        <div class="card-title">🔐 Riwayat Login Terbaru</div>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>Browser</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestLogins as $login)
                    <tr>
                        <td>{{ $login->user->name ?? '-' }}</td>
                        <td>{{ $login->email }}</td>
                        <td>{{ $login->ip_address }}</td>
                        <td>{{ $login->browser }} ({{ $login->os }})</td>
                        <td>
                            @if (str_contains($login->status, 'Berhasil'))
                                <span class="badge badge-success">{{ $login->status }}</span>
                            @else
                                <span class="badge badge-danger">{{ $login->status }}</span>
                            @endif
                        </td>
                        <td>{{ $login->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #718096;">
                            Belum ada riwayat login
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
