<!DOCTYPE html>
<html lang="id" class="{{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Diskominfo Kabupaten Sanggau')</title>
    <meta name="description" content="@yield('description', 'Portal resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau')">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('logo-sanggau.png') }}" type="image/png">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/web.css') }}">

    @stack('styles')
</head>
<body>

{{-- Topbar --}}
<div class="topbar" id="topbar">
    <div class="container">
        <div class="topbar-inner">
            <div class="topbar-left">
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                </span>
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 8.81a19.79 19.79 0 01-3.07-8.63A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>
                    (0564) 21234
                </span>
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>
                    diskominfo@sanggau.go.id
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Navbar --}}
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="nav-logo">
                <div class="nav-logo-ring">
                    <img src="{{ asset('logo-sanggau.png') }}" alt="Logo Diskominfo Sanggau" class="nav-logo-img"
                         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Lambang_Kabupaten_Sanggau.png/600px-Lambang_Kabupaten_Sanggau.png'">
                </div>
                <div class="nav-logo-text">
                    <div class="nav-logo-title">Dinas Komunikasi dan Informatika</div>
                    <div class="nav-logo-sub">Kabupaten Sanggau</div>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">Profil</a></li>
                <li class="has-dropdown">
                    <a href="{{ route('berita') }}" class="{{ request()->routeIs('berita*') ? 'active' : '' }}">
                        Berita <span class="nav-chevron">▾</span>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('berita') }}">› Berita Terkini</a>
                        <a href="{{ route('pengumuman') }}">› Pengumuman</a>
                        <a href="{{ route('agenda') }}">› Agenda</a>
                    </div>
                </li>
                <li><a href="{{ route('layanan') }}" class="{{ request()->routeIs('layanan') ? 'active' : '' }}">Layanan</a></li>
                <li><a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri</a></li>
                <li class="has-dropdown">
                    <a href="#" class="{{ request()->routeIs('ppid','download') ? 'active' : '' }}">
                        PPID <span class="nav-chevron">▾</span>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('ppid') }}">› Informasi Publik</a>
                        <a href="{{ route('download') }}">› Download Dokumen</a>
                    </div>
                </li>
                <li><a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'active' : '' }}">Kontak</a></li>
            </ul>

            {{-- Controls --}}
            <div class="nav-controls">
                <button class="theme-btn" id="themeBtn" aria-label="Toggle tema" onclick="toggleTheme()">
                    <svg id="iconSun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <svg id="iconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </button>
                <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mobile-overlay" id="mobileOverlay" onclick="toggleMenu()"></div>
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-header">
        <div style="display:flex;align-items:center;gap:.75rem">
            <img src="{{ asset('logo-sanggau.png') }}" alt="Logo" style="width:36px;height:36px;object-fit:contain">
            <div>
                <div style="font-weight:800;font-size:.9rem">Diskominfo</div>
                <div style="font-size:.65rem;color:#1A56DB;font-weight:700;text-transform:uppercase;letter-spacing:.06em">Kab. Sanggau</div>
            </div>
        </div>
        <button onclick="toggleMenu()" aria-label="Tutup" class="mobile-close">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="mobile-body">
        <a href="{{ route('home') }}" class="mobile-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('profil') }}" class="mobile-link {{ request()->routeIs('profil') ? 'active' : '' }}">Profil</a>
        <a href="{{ route('berita') }}" class="mobile-link {{ request()->routeIs('berita*') ? 'active' : '' }}">Berita</a>
        <a href="{{ route('pengumuman') }}" class="mobile-link {{ request()->routeIs('pengumuman') ? 'active' : '' }}" style="padding-left:2rem;font-size:.85rem">› Pengumuman</a>
        <a href="{{ route('agenda') }}" class="mobile-link {{ request()->routeIs('agenda') ? 'active' : '' }}" style="padding-left:2rem;font-size:.85rem">› Agenda</a>
        <a href="{{ route('layanan') }}" class="mobile-link {{ request()->routeIs('layanan') ? 'active' : '' }}">Layanan</a>
        <a href="{{ route('galeri') }}" class="mobile-link {{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri</a>
        <a href="{{ route('ppid') }}" class="mobile-link {{ request()->routeIs('ppid') ? 'active' : '' }}">PPID</a>
        <a href="{{ route('download') }}" class="mobile-link {{ request()->routeIs('download') ? 'active' : '' }}" style="padding-left:2rem;font-size:.85rem">› Download Dokumen</a>
        <a href="{{ route('kontak') }}" class="mobile-link {{ request()->routeIs('kontak') ? 'active' : '' }}">Kontak</a>
    </div>
    <div class="mobile-footer">
        <a href="{{ route('home') }}" style="display:block;padding:.6rem .85rem;color:#64748B;font-size:.78rem;text-decoration:none">🌐 Lihat Website</a>
    </div>
</div>

{{-- Main Content --}}
<main>
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

{{-- Footer --}}
@include('web.layouts.footer')

{{-- Scroll to top --}}
<button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</button>

{{-- JS --}}
<script src="{{ asset('js/web.js') }}"></script>
@stack('scripts')
</body>
</html>
