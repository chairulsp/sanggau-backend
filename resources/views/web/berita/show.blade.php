@extends('layouts.app')

@section('title', $berita->judul . ' - Diskominfo Kabupaten Sanggau')
@section('meta_description', Str::limit($berita->ringkasan, 155))

@push('styles')
<style>
    .article-content {
        font-size: 1.05rem;
        line-height: 1.85;
        color: var(--text-secondary);
    }
    .article-content h2 {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 2rem 0 1rem;
        color: var(--text-primary);
    }
    .article-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 1.75rem 0 0.875rem;
        color: var(--text-primary);
    }
    .article-content p {
        margin-bottom: 1.25rem;
    }
    .article-content ul, .article-content ol {
        margin: 1.25rem 0;
        padding-left: 2rem;
    }
    .article-content li {
        margin-bottom: 0.5rem;
    }
    .article-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: var(--text-muted);
    }
    .article-content img {
        max-width: 100%;
        border-radius: 12px;
        margin: 1.5rem 0;
    }
    
    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg-surface);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
    }
    .share-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
    }
    
    .related-card {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem;
        text-decoration: none;
        display: flex;
        gap: 1rem;
        transition: all 0.2s;
        color: inherit;
    }
    .related-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-2px);
        border-color: var(--primary);
    }
    .related-thumb {
        width: 100px;
        height: 70px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #EEF2FF;
    }
    .related-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    @media (max-width: 768px) {
        .article-layout {
            grid-template-columns: 1fr !important;
        }
        .sidebar {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('berita') }}">Berita</a>
            <span>/</span>
            <span>{{ Str::limit($berita->judul, 50) }}</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="article-layout" style="display: grid; grid-template-columns: 1fr 340px; gap: 2.5rem;">
            
            {{-- Main Content --}}
            <article>
                {{-- Category Badge --}}
                <div style="margin-bottom: 1rem;">
                    <span style="display: inline-flex; padding: 0.4rem 1rem; background: rgba(26,86,219,0.1); color: var(--primary); border-radius: 999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        {{ $berita->kategori ?? 'Berita' }}
                    </span>
                </div>
                
                {{-- Title --}}
                <h1 style="font-size: clamp(1.75rem, 4vw, 2.25rem); font-weight: 900; line-height: 1.2; margin-bottom: 1rem; color: var(--text-primary);">
                    {{ $berita->judul }}
                </h1>
                
                {{-- Meta Info --}}
                <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #1A56DB, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                            {{ substr($berita->penulis ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $berita->penulis ?? 'Admin' }}</div>
                            <div style="font-size: 0.8rem;">Penulis</div>
                        </div>
                    </div>
                    <div style="color: var(--text-muted); font-size: 0.9rem;">
                        <div style="font-weight: 600;">📅 {{ $berita->created_at->format('d F Y') }}</div>
                        <div style="font-size: 0.8rem;">{{ $berita->created_at->diffForHumans() }}</div>
                    </div>
                    @if($berita->views_count)
                    <div style="color: var(--text-muted); font-size: 0.9rem;">
                        <div style="font-weight: 600;">👁️ {{ number_format($berita->views_count) }}</div>
                        <div style="font-size: 0.8rem;">Dibaca</div>
                    </div>
                    @endif
                </div>
                
                {{-- Featured Image --}}
                @if($berita->gambar)
                <div style="margin-bottom: 2.5rem; border-radius: 16px; overflow: hidden;">
                    <img src="{{ Storage::url($berita->gambar) }}" alt="{{ $berita->judul }}" 
                         style="width: 100%; height: auto; display: block;"
                         onerror="this.style.display='none'">
                </div>
                @endif
                
                {{-- Ringkasan --}}
                @if($berita->ringkasan)
                <div style="background: #F8FAFF; border-left: 4px solid var(--primary); padding: 1.25rem 1.5rem; border-radius: 0 12px 12px 0; margin-bottom: 2rem;">
                    <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.7; color: var(--text-primary); margin: 0;">
                        {{ $berita->ringkasan }}
                    </p>
                </div>
                @endif
                
                {{-- Content --}}
                <div class="article-content">
                    {!! $berita->konten !!}
                </div>
                
                {{-- Share Buttons --}}
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Bagikan Artikel
                    </div>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" class="share-btn" title="Bagikan ke Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($berita->judul) }}" target="_blank" rel="noopener" class="share-btn" title="Bagikan ke Twitter">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . request()->fullUrl()) }}" target="_blank" rel="noopener" class="share-btn" title="Bagikan ke WhatsApp">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </a>
                        <button onclick="copyLink()" class="share-btn" title="Salin Link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </article>
            
            {{-- Sidebar --}}
            <aside class="sidebar">
                {{-- Related News --}}
                @if($related->count() > 0)
                <div style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1.25rem; color: var(--text-primary);">
                        📰 Berita Terkait
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($related as $rel)
                        <a href="{{ route('berita.show', $rel->slug) }}" class="related-card">
                            <div class="related-thumb">
                                @if($rel->gambar)
                                <img src="{{ Storage::url($rel->gambar) }}" alt="{{ $rel->judul }}"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27100%27 height=%2770%27%3E%3Crect fill=%27%23EEF2FF%27 width=%27100%27 height=%2770%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 dominant-baseline=%27middle%27 text-anchor=%27middle%27 fill=%27%2394A3B8%27 font-size=%2716%27%3E📰%3C/text%3E%3C/svg%3E'">
                                @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    📰
                                </div>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 0.85rem; font-weight: 700; line-height: 1.4; margin-bottom: 0.35rem; color: var(--text-primary);">
                                    {{ Str::limit($rel->judul, 60) }}
                                </h4>
                                <span style="font-size: 0.72rem; color: var(--text-muted);">
                                    {{ $rel->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- Recent News Widget --}}
                <div style="background: linear-gradient(135deg, #1A56DB, #3B82F6); border-radius: 16px; padding: 1.5rem; color: white;">
                    <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.75rem;">
                        📢 Ikuti Kami
                    </h3>
                    <p style="font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.25rem; opacity: 0.9;">
                        Dapatkan informasi dan berita terbaru dari Diskominfo Sanggau
                    </p>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="#" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 0.65rem; background: rgba(255,255,255,0.2); border-radius: 8px; color: white; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;">
                            Facebook
                        </a>
                        <a href="#" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 0.65rem; background: rgba(255,255,255,0.2); border-radius: 8px; color: white; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;">
                            Instagram
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Link berhasil disalin!');
        }).catch(() => {
            // Fallback untuk browser lama
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Link berhasil disalin!');
        });
    }
</script>
@endpush
