@extends('layouts.app')

@section('title', $laman->judul . ' - ' . config('app.name'))

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">{{ $laman->judul }}</span>
        </div>
        <h1>{{ $laman->judul }}</h1>
        @if($laman->deskripsi)
            <p>{{ $laman->deskripsi }}</p>
        @endif
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="max-width: 920px; margin: 0 auto;">
            <article style="background: var(--bg-surface); border-radius: 20px; border: 1px solid var(--border); padding: 3rem; box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
                
                {{-- Meta Info --}}
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; padding-bottom: 1.5rem; margin-bottom: 2rem; border-bottom: 2px solid #EFF6FF; font-size: 0.85rem; color: var(--text-muted);">
                    @if($laman->penulis)
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <span>✍️</span>
                            <span>{{ $laman->penulis }}</span>
                        </div>
                    @endif
                    <div style="display: flex; align-items: center; gap: 0.4rem;">
                        <span>📅</span>
                        <span>{{ \Carbon\Carbon::parse($laman->created_at)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    @if($laman->updated_at != $laman->created_at)
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <span>🔄</span>
                            <span>Diperbarui {{ \Carbon\Carbon::parse($laman->updated_at)->locale('id')->isoFormat('D MMM YYYY') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="prose" style="line-height: 1.8; color: var(--text-secondary); font-size: 1.05rem;">
                    {!! nl2br(e($laman->konten)) !!}
                </div>

                {{-- Tags (if any) --}}
                @if(isset($laman->tags) && $laman->tags)
                    <div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">
                            🏷️ Tags
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @foreach(explode(',', $laman->tags) as $tag)
                                <span style="padding: 0.35rem 0.85rem; background: var(--primary-soft); color: var(--primary); border-radius: 999px; font-size: 0.8rem; font-weight: 600;">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            {{-- Related Pages (if any) --}}
            @if(isset($relatedPages) && $relatedPages->count() > 0)
                <div style="margin-top: 3rem;">
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center;">
                        📚 Halaman Terkait
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                        @foreach($relatedPages as $related)
                            <a href="{{ route('laman.show', $related->slug) }}" 
                                style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 1.5rem; text-decoration: none; transition: all 0.2s;"
                                onmouseenter="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.08)'"
                                onmouseleave="this.style.transform=''; this.style.boxShadow=''">
                                <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; line-height: 1.4;">
                                    {{ $related->judul }}
                                </h4>
                                @if($related->deskripsi)
                                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
                                        {{ Str::limit($related->deskripsi, 80) }}
                                    </p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
.prose h1, .prose h2, .prose h3 {
    color: var(--text-primary);
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}
.prose h1 { font-size: 1.8rem; }
.prose h2 { font-size: 1.5rem; }
.prose h3 { font-size: 1.2rem; }
.prose p {
    margin-bottom: 1.25rem;
}
.prose ul, .prose ol {
    margin-left: 1.5rem;
    margin-bottom: 1.25rem;
}
.prose li {
    margin-bottom: 0.5rem;
}
.prose a {
    color: var(--primary);
    text-decoration: underline;
}
.prose a:hover {
    color: var(--primary-dark);
}
.prose strong {
    font-weight: 700;
    color: var(--text-primary);
}
.prose code {
    background: #F1F5F9;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9em;
    font-family: 'Courier New', monospace;
}
.prose blockquote {
    border-left: 4px solid var(--primary);
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: var(--text-secondary);
}
</style>
@endsection
