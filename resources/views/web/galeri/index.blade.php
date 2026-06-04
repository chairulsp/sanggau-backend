@extends('layouts.app')

@section('title', 'Galeri Media - Diskominfo Kabupaten Sanggau')
@section('meta_description', 'Galeri foto dan video dokumentasi kegiatan Dinas Komunikasi dan Informatika Kabupaten Sanggau')

@push('styles')
<style>
    .tab-btn {
        padding: 0.65rem 1.75rem;
        border-radius: 999px;
        border: 1.5px solid;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.9rem;
        font-family: inherit;
        transition: all 0.2s;
        background: white;
        color: var(--text-secondary);
        border-color: var(--border);
    }
    .tab-btn.active {
        border-color: var(--primary);
        background: var(--primary);
        color: white;
    }
    .tab-btn:not(.active):hover {
        background: #EEF2FF;
        color: var(--primary);
    }
    
    .foto-item {
        border-radius: 12px;
        overflow: hidden;
        cursor: zoom-in;
        position: relative;
        padding-bottom: 75%;
        background: #EEF2FF;
        transition: transform 0.3s;
    }
    .foto-item:hover {
        transform: scale(1.03);
    }
    .foto-item img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .video-item {
        border-radius: 16px;
        overflow: hidden;
        background: var(--bg-surface);
        border: 1px solid var(--border);
        transition: all 0.25s;
        cursor: pointer;
    }
    .video-item:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.12);
    }
    .video-thumb {
        position: relative;
        padding-bottom: 56.25%;
        background: #000;
        overflow: hidden;
    }
    .video-thumb img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .play-button {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.3);
    }
    .play-button-inner {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(255,255,255,0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        padding-left: 4px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        transition: transform 0.2s;
    }
    .video-item:hover .play-button-inner {
        transform: scale(1.1);
    }
    
    /* Lightbox */
    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.92);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: zoom-out;
        padding: 1rem;
        animation: fadeIn 0.2s ease-out;
    }
    .lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        position: relative;
    }
    .lightbox-img {
        max-width: 100%;
        max-height: 85vh;
        border-radius: 12px;
        object-fit: contain;
    }
    .lightbox-close {
        position: absolute;
        top: -0.75rem;
        right: -0.75rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @media (max-width: 768px) {
        .galeri-grid {
            grid-template-columns: 1fr 1fr !important;
        }
    }
    @media (max-width: 480px) {
        .galeri-grid {
            grid-template-columns: 1fr !important;
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
            <span>Galeri</span>
        </div>
        <h1>Galeri Media</h1>
        <p>Foto dan video dokumentasi kegiatan Diskominfo Kabupaten Sanggau</p>
    </div>
</div>

<section class="section">
    <div class="container">
        
        {{-- Tabs + Counter --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 2rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="tab-btn active" data-tab="foto" onclick="switchTab('foto')">
                    📷 Galeri Foto
                </button>
                <button class="tab-btn" data-tab="video" onclick="switchTab('video')">
                    🎬 Galeri Video
                </button>
            </div>
            <div style="font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">
                <span id="fotoCount">{{ $galeri->count() }} foto</span>
                <span id="videoCount" style="display: none;">{{ $video->count() }} video</span>
            </div>
        </div>
        
        {{-- Tab Content: Foto --}}
        <div id="tabFoto" class="tab-content">
            @if($galeri->count() > 0)
            <div class="galeri-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                @foreach($galeri as $foto)
                <div class="foto-item" onclick="openLightbox('{{ Storage::url($foto->gambar) }}', '{{ $foto->judul }}', '{{ $foto->created_at->format('d M Y') }}')">
                    <img src="{{ Storage::url($foto->gambar) }}" alt="{{ $foto->judul }}"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27200%27 height=%27150%27%3E%3Crect fill=%27%23EEF2FF%27 width=%27200%27 height=%27150%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 dominant-baseline=%27middle%27 text-anchor=%27middle%27 fill=%27%2394A3B8%27 font-size=%2732%27%3E📷%3C/text%3E%3C/svg%3E'">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.65), transparent 55%); opacity: 0; transition: opacity 0.3s;" onmouseenter="this.style.opacity='1'" onmouseleave="this.style.opacity='0'">
                        <div style="position: absolute; bottom: 0.75rem; left: 0.75rem; right: 0.75rem;">
                            <div style="color: white; font-weight: 700; font-size: 0.85rem; line-height: 1.3;">{{ $foto->judul }}</div>
                            <div style="color: rgba(255,255,255,0.7); font-size: 0.72rem;">{{ $foto->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.9); border-radius: 6px; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 700; color: var(--primary);">
                        🔍 Zoom
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 5rem; color: var(--text-muted);">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📷</div>
                <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Belum ada foto</div>
                <div style="font-size: 0.9rem;">Galeri foto akan segera diperbarui</div>
            </div>
            @endif
        </div>
        
        {{-- Tab Content: Video --}}
        <div id="tabVideo" class="tab-content" style="display: none;">
            @if($video->count() > 0)
            <div class="galeri-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                @foreach($video as $vid)
                @php
                    $videoId = null;
                    if (preg_match('/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $vid->url_youtube, $match)) {
                        $videoId = $match[1];
                    }
                    $thumbnail = $videoId ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg" : '';
                @endphp
                <div class="video-item" onclick="playVideo('{{ $videoId }}', '{{ $vid->judul }}')">
                    <div class="video-thumb">
                        @if($thumbnail)
                        <img src="{{ $thumbnail }}" alt="{{ $vid->judul }}">
                        @else
                        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, #1E3A8A, #1A56DB);"></div>
                        @endif
                        <div class="play-button">
                            <div class="play-button-inner">▶</div>
                        </div>
                        <div style="position: absolute; bottom: 0.5rem; right: 0.5rem; background: rgba(0,0,0,0.75); color: white; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700;">
                            YouTube
                        </div>
                    </div>
                    <div style="padding: 1.1rem 1.25rem;">
                        <h3 style="font-size: 0.95rem; line-height: 1.4; margin-bottom: 0.4rem; font-weight: 700; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $vid->judul }}
                        </h3>
                        @if($vid->deskripsi)
                        <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 0.5rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $vid->deskripsi }}
                        </p>
                        @endif
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--text-muted);">
                            <span>🎬 Diskominfo Sanggau</span>
                            @if($vid->tanggal)
                            <span>·</span>
                            <span>📅 {{ \Carbon\Carbon::parse($vid->tanggal)->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 5rem; color: var(--text-muted);">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">🎬</div>
                <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Belum ada video</div>
                <div style="font-size: 0.9rem;">Galeri video akan segera diperbarui</div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Lightbox Photo --}}
<div id="lightbox" class="lightbox" style="display: none;" onclick="closeLightbox()">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <img id="lightboxImg" class="lightbox-img" src="" alt="">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); border-radius: 0 0 12px 12px; padding: 1.5rem 1rem 0.75rem;">
            <div id="lightboxTitle" style="color: white; font-weight: 700;"></div>
            <div id="lightboxDate" style="color: rgba(255,255,255,0.6); font-size: 0.8rem;"></div>
        </div>
        <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    </div>
</div>

{{-- Video Modal --}}
<div id="videoModal" class="lightbox" style="display: none;" onclick="closeVideo()">
    <div onclick="event.stopPropagation()" style="width: min(860px, 95vw); position: relative;">
        <div style="position: relative; padding-bottom: 56.25%; background: #000; border-radius: 12px; overflow: hidden;">
            <iframe id="videoFrame" src="" title="" style="position: absolute; inset: 0; width: 100%; height: 100%; border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div style="margin-top: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
            <div id="videoTitle" style="color: white; font-weight: 700; font-size: 1rem;"></div>
            <button onclick="closeVideo()" style="background: rgba(255,255,255,0.15); border: none; color: white; padding: 0.4rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.85rem;">
                ✕ Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        // Update buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (btn.dataset.tab === tab) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Update content
        if (tab === 'foto') {
            document.getElementById('tabFoto').style.display = '';
            document.getElementById('tabVideo').style.display = 'none';
            document.getElementById('fotoCount').style.display = '';
            document.getElementById('videoCount').style.display = 'none';
        } else {
            document.getElementById('tabFoto').style.display = 'none';
            document.getElementById('tabVideo').style.display = '';
            document.getElementById('fotoCount').style.display = 'none';
            document.getElementById('videoCount').style.display = '';
        }
    }
    
    function openLightbox(src, title, date) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightboxTitle').textContent = title;
        document.getElementById('lightboxDate').textContent = date;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
    
    function playVideo(videoId, title) {
        if (!videoId) return;
        
        const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        document.getElementById('videoFrame').src = embedUrl;
        document.getElementById('videoTitle').textContent = title;
        document.getElementById('videoModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeVideo() {
        document.getElementById('videoFrame').src = '';
        document.getElementById('videoModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    
    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLightbox();
            closeVideo();
        }
    });
</script>
@endpush
