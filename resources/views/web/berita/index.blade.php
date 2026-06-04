@extends('layouts.app')

@section('title', 'Berita & Informasi - Diskominfo Kabupaten Sanggau')
@section('meta_description', 'Berita terkini dan informasi dari Dinas Komunikasi dan Informatika Kabupaten Sanggau')

@push('styles')
<style>
    .news-card {
        background: var(--bg-surface);
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
        display: flex;
        flex-direction: column;
        height: 100%;
        text-decoration: none;
        color: inherit;
    }
    .news-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 48px rgba(26,86,219,0.12), 0 8px 16px rgba(0,0,0,0.06);
        border-color: rgba(26,86,219,0.2);
    }
    .news-card .card-thumb {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/10;
        background: linear-gradient(135deg, #EEF2FF, #DBEAFE);
    }
    .news-card .card-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
    }
    .news-card:hover .card-thumb img {
        transform: scale(1.08);
    }
    .cat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .cat-pill-blue {
        background: rgba(26,86,219,0.12);
        color: #1A56DB;
    }
    .cat-pill-white {
        background: rgba(255,255,255,0.92);
        color: #1A56DB;
        backdrop-filter: blur(8px);
    }
    @media (max-width: 768px) {
        .berita-grid {
            grid-template-columns: 1fr 1fr !important;
        }
    }
    @media (max-width: 480px) {
        .berita-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Berita</span>
        </div>
        <h1>Berita & Informasi</h1>
        <p>Berita terkini dan informasi dari Dinas Komunikasi dan Informatika Kabupaten Sanggau</p>
    </div>
</div>

<section class="section">
    <div class="container">
        
        {{-- Search Bar --}}
        <div style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 0.75rem 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 0.75rem; align-items: center; box-shadow: 0 2px 12px rgba(0,0,0,0.04);">
            <span style="font-size: 1.1rem; opacity: 0.5;">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari berita..." 
                   style="flex: 1; padding: 0.5rem 0; border-radius: 8px; border: none; font-family: inherit; font-size: 0.9rem; outline: none; background: transparent;">
        </div>
        
        {{-- Category Filters --}}
        @if($kategori->count() > 0)
        <div style="margin-bottom: 2rem; overflow-x: auto;">
            <div style="display: flex; gap: 0.4rem; padding-bottom: 0.25rem;">
                <button class="cat-filter active" data-category="">
                    Semua
                    <span class="cat-count">{{ $berita->total() }}</span>
                </button>
                @foreach($kategori as $kat)
                <button class="cat-filter" data-category="{{ $kat }}">
                    {{ $kat }}
                </button>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- News Grid --}}
        <div class="berita-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
            @forelse($berita as $news)
            <a href="{{ route('berita.show', $news->slug) }}" class="news-card" data-category="{{ $news->kategori }}">
                <div class="card-thumb">
                    @if($news->gambar)
                    <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80'">
                    @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                        📰
                    </div>
                    @endif
                    <div style="position: absolute; top: 0.85rem; left: 0.85rem; display: flex; gap: 0.3rem; z-index: 2;">
                        <span class="cat-pill cat-pill-white">{{ $news->kategori ?? 'Berita' }}</span>
                    </div>
                </div>
                <div style="padding: 1.35rem 1.5rem 1.5rem; flex: 1; display: flex; flex-direction: column;">
                    <h3 style="font-size: 1rem; line-height: 1.45; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $news->judul }}
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.65; margin-bottom: 1rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $news->ringkasan }}
                    </p>
                    <div style="margin-top: auto; padding-top: 0.85rem; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--text-muted);">
                        <span>✍️ {{ $news->penulis ?? 'Admin' }}</span>
                        <span>·</span>
                        <span>📅 {{ $news->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 5rem 2rem; background: var(--bg-surface); border-radius: 20px; border: 1px solid var(--border); color: var(--text-muted);">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📭</div>
                <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.4rem;">Tidak ada berita</div>
                <div style="font-size: 0.9rem;">Berita akan segera diperbarui</div>
            </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        @if($berita->hasPages())
        <div style="margin-top: 3rem; display: flex; justify-content: center;">
            {{ $berita->links() }}
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const newsCards = document.querySelectorAll('.news-card');
    
    searchInput?.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        
        newsCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const content = card.querySelector('p').textContent.toLowerCase();
            
            if (title.includes(query) || content.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // Category filter
    const catFilters = document.querySelectorAll('.cat-filter');
    catFilters.forEach(btn => {
        btn.addEventListener('click', () => {
            const category = btn.dataset.category;
            
            // Update active state
            catFilters.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Filter cards
            newsCards.forEach(card => {
                const cardCat = card.dataset.category;
                if (!category || cardCat === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

<style>
    .cat-filter {
        padding: 0.6rem 1.1rem;
        border: none;
        border-radius: 999px;
        cursor: pointer;
        font-family: inherit;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.25s;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--bg-surface);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }
    .cat-filter.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(26,86,219,0.3);
        border-color: var(--primary);
    }
    .cat-filter:not(.active):hover {
        background: #EEF2FF;
        color: var(--primary);
        border-color: #BFDBFE;
    }
    .cat-count {
        font-size: 0.62rem;
        padding: 0.08rem 0.4rem;
        border-radius: 999px;
        font-weight: 800;
        background: rgba(255,255,255,0.25);
    }
    .cat-filter.active .cat-count {
        background: rgba(255,255,255,0.25);
    }
</style>
@endpush
