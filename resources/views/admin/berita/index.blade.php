@extends('admin.layouts.app')

@section('title', 'Manajemen Berita')

@section('content')
<style>
    .filter-bar {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-form {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #2d3748;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn-filter {
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .btn-filter:hover {
        background: #5a67d8;
    }

    .btn-reset {
        padding: 10px 20px;
        background: #e2e8f0;
        color: #2d3748;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.75rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit {
        background: #4299e1;
        color: white;
    }

    .btn-delete {
        background: #f56565;
        color: white;
        border: none;
        cursor: pointer;
    }

    .thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        text-decoration: none;
        color: #2d3748;
    }

    .pagination .active span {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .toggle-status {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .toggle-status:hover {
        transform: scale(1.1);
    }
</style>

<div class="card">
    <div class="card-header">
        <div class="card-title">📰 Daftar Berita</div>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
            ➕ Tambah Berita
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form action="{{ route('admin.berita.index') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="search">Cari</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    class="form-control" 
                    placeholder="Cari judul, konten, kategori..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-filter">🔍 Filter</button>
                <a href="{{ route('admin.berita.index') }}" class="btn-reset">🔄 Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Judul</th>
                    <th style="width: 120px;">Kategori</th>
                    <th style="width: 120px;">Penulis</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($beritas as $berita)
                    <tr>
                        <td>
                            @if ($berita->gambar)
                                <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="thumbnail">
                            @else
                                <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    📰
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600; margin-bottom: 4px;">{{ Str::limit($berita->judul, 60) }}</div>
                            <div style="font-size: 0.75rem; color: #718096;">
                                {{ Str::limit($berita->ringkasan ?? strip_tags($berita->konten), 80) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: #bee3f8; color: #2c5282;">
                                {{ $berita->kategori }}
                            </span>
                        </td>
                        <td>{{ $berita->penulis }}</td>
                        <td>
                            <span 
                                class="badge toggle-status {{ $berita->aktif ? 'badge-success' : 'badge-warning' }}"
                                onclick="toggleStatus({{ $berita->id }})"
                                id="status-{{ $berita->id }}"
                                title="Klik untuk mengubah status"
                            >
                                {{ $berita->aktif ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">{{ $berita->created_at->format('d/m/Y') }}</div>
                            <div style="font-size: 0.75rem; color: #718096;">{{ $berita->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('berita.show', $berita->slug) }}" target="_blank" class="btn-sm" style="background: #48bb78; color: white;" title="Lihat">
                                    👁️
                                </a>
                                <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn-sm btn-edit" title="Edit">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete" title="Hapus">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #718096;">
                            <div style="font-size: 3rem; margin-bottom: 10px;">📭</div>
                            <div style="font-weight: 600; margin-bottom: 5px;">Belum Ada Berita</div>
                            <div style="font-size: 0.875rem;">Mulai tambahkan berita pertama Anda!</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($beritas->hasPages())
        <div class="pagination">
            {{ $beritas->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleStatus(id) {
    if (!confirm('Yakin ingin mengubah status berita ini?')) {
        return;
    }

    fetch(`/admin/berita/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`status-${id}`);
            if (data.aktif) {
                badge.classList.remove('badge-warning');
                badge.classList.add('badge-success');
                badge.textContent = 'Published';
            } else {
                badge.classList.remove('badge-success');
                badge.classList.add('badge-warning');
                badge.textContent = 'Draft';
            }
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status!');
    });
}
</script>
@endpush
@endsection
