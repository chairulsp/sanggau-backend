@php
    $profil = \App\Models\ProfilDiskominfo::first();
    $menus = \App\Models\Menu::where('aktif', true)->orderBy('urutan')->get();
    $activePages = \App\Models\Laman::where('aktif', true)->get();
    
    // Default navigation if no menu from database
    $defaultNav = [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Profil', 'url' => route('profil')],
        ['label' => 'Berita', 'url' => route('berita')],
        ['label' => 'Pengumuman', 'url' => route('pengumuman')],
        ['label' => 'Layanan', 'url' => route('layanan')],
        ['label' => 'Agenda', 'url' => route('agenda')],
        ['label' => 'Galeri', 'url' => route('galeri')],
        ['label' => 'PPID', 'url' => route('ppid'), 'children' => [
            ['label' => 'Informasi Publik', 'url' => route('ppid')],
            ['label' => 'Download Dokumen', 'url' => route('download')],
        ]],
        ['label' => 'Kontak', 'url' => route('kontak')],
    ];
    
    // Build navigation from database or use default
    $navItems = [];
    if ($menus->count() > 0) {
        foreach ($menus as $menu) {
            $upper = strtoupper($menu->label);
            if ($upper === 'PPID' || $upper === 'PPID & DOKUMEN') {
                $navItems[] = [
                    'label' => $menu->label,
                    'url' => $menu->url,
                    'children' => [
                        ['label' => 'Informasi Publik', 'url' => route('ppid')],
                        ['label' => 'Download Dokumen', 'url' => route('download')],
                    ]
                ];
            } else {
                $navItems[] = ['label' => $menu->label, 'url' => $menu->url];
            }
        }
        
        // Add active pages as dropdown if exists
        if ($activePages->count() > 0) {
            $lamanChildren = $activePages->map(fn($l) => [
                'label' => $l->judul,
                'url' => route('laman.show', $l->slug)
            ])->toArray();
            
            // Insert before Kontak or at end
            $kontakIndex = collect($navItems)->search(fn($item) => strtoupper($item['label']) === 'KONTAK');
            $insertIndex = $kontakIndex !== false ? $kontakIndex : count($navItems);
            array_splice($navItems, $insertIndex, 0, [
                ['label' => 'Laman', 'url' => '#', 'children' => $lamanChildren]
            ]);
        }
    } else {
        $navItems = $defaultNav;
    }
@endphp

<style>
    /* ── Topbar ── */
    .topbar {
        background: #0F2061;
        color: rgba(255,255,255,.75);
        font-size: .75rem;
        font-weight: 500;
        padding: .45rem 0;
        position: relative;
        z-index: 200;
        transition: transform .3s ease;
    }
    .topbar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .topbar-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .topbar-left span {
        display: flex;
        align-items: center;
        gap: .3rem;
    }
    .topbar.hidden {
        transform: translateY(-100%);
    }

    /* ── Navbar ── */
    .navbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(255,255,255,.95);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid #E2E8F0;
        transition: box-shadow .3s ease, background .3s ease;
    }
    .navbar.scrolled {
        box-shadow: 0 4px 24px rgba(0,0,0,.1);
        background: rgba(255,255,255,.98);
    }
    .dark .navbar {
        background: rgba(15,32,97,.92);
        border-color: rgba(255,255,255,.08);
    }
    .dark .navbar.scrolled {
        background: rgba(7,14,55,.97);
    }

    .nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .875rem 0;
    }

    /* Logo */
    .nav-logo {
        display: flex;
        align-items: center;
        gap: .75rem;
        text-decoration: none;
        flex-shrink: 0;
    }
    .nav-logo-img {
        width: 44px;
        height: 44px;
        object-fit: contain;
        filter: drop-shadow(0 2px 8px rgba(26,86,219,.25));
        transition: transform .3s ease;
    }
    .nav-logo:hover .nav-logo-img {
        transform: scale(1.08) rotate(-3deg);
    }
    .nav-logo-text {
        display: flex;
        flex-direction: column;
    }
    .nav-logo-title {
        font-size: .82rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
        letter-spacing: -.01em;
    }
    .dark .nav-logo-title {
        color: white;
    }
    .nav-logo-sub {
        font-size: .62rem;
        color: #1A56DB;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        display: flex;
        align-items: center;
        gap: .3rem;
        margin-top: .1rem;
    }
    .nav-logo-sub::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1A56DB;
        animation: logoPulse 2s ease-in-out infinite;
    }
    @keyframes logoPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .5; transform: scale(1.3); }
    }
    .dark .nav-logo-sub {
        color: #60A5FA;
    }
    .dark .nav-logo-sub::before {
        background: #60A5FA;
    }

    /* Desktop menu */
    .nav-menu {
        display: flex;
        align-items: center;
        gap: .25rem;
        list-style: none;
    }
    .nav-menu li {
        position: relative;
    }
    .nav-menu li a {
        display: flex;
        align-items: center;
        gap: .3rem;
        padding: .5rem .875rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: all .2s ease;
        white-space: nowrap;
    }
    .dark .nav-menu li a {
        color: rgba(255,255,255,.8);
    }
    .nav-menu li a:hover, .nav-menu li a.active {
        background: #EFF6FF;
        color: #1A56DB;
    }
    .dark .nav-menu li a:hover, .dark .nav-menu li a.active {
        background: rgba(255,255,255,.1);
        color: white;
    }
    .nav-menu li a.active {
        font-weight: 700;
    }

    /* Dropdown */
    .nav-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border-radius: 14px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 16px 48px rgba(0,0,0,.12);
        min-width: 210px;
        padding: .5rem;
        opacity: 0;
        pointer-events: none;
        transform: translateX(-50%) translateY(-8px);
        transition: all .25s ease;
        z-index: 99;
    }
    .dark .nav-dropdown {
        background: #0D1B4A;
        border-color: rgba(255,255,255,.1);
        box-shadow: 0 16px 48px rgba(0,0,0,.4);
    }
    .nav-menu li:hover .nav-dropdown {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }
    .nav-dropdown a {
        display: flex !important;
        align-items: center;
        gap: .5rem;
        padding: .55rem .875rem !important;
        border-radius: 8px !important;
        font-size: .83rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
        text-decoration: none;
        white-space: nowrap;
    }
    .dark .nav-dropdown a {
        color: rgba(255,255,255,.8) !important;
    }
    .nav-dropdown a:hover {
        background: #EFF6FF !important;
        color: #1A56DB !important;
    }
    .dark .nav-dropdown a:hover {
        background: rgba(255,255,255,.08) !important;
        color: white !important;
    }
    .nav-dropdown::before {
        content: '';
        position: absolute;
        top: -6px;
        left: 50%;
        transform: translateX(-50%);
        width: 12px;
        height: 12px;
        background: white;
        border: 1px solid #E2E8F0;
        border-right: none;
        border-bottom: none;
        transform: translateX(-50%) rotate(45deg);
    }
    .dark .nav-dropdown::before {
        background: #0D1B4A;
        border-color: rgba(255,255,255,.1);
    }

    /* Chevron indicator */
    .nav-chevron {
        font-size: .55rem;
        opacity: .5;
        transition: transform .2s ease;
    }
    .nav-menu li:hover .nav-chevron {
        transform: rotate(180deg);
        opacity: .8;
    }

    /* Hamburger */
    .hamburger {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: .5rem;
        border-radius: 8px;
        transition: background .2s;
    }
    .hamburger:hover {
        background: #F1F5F9;
    }
    .hamburger span {
        display: block;
        width: 22px;
        height: 2px;
        background: #374151;
        border-radius: 2px;
        transition: all .3s cubic-bezier(.4,0,.2,1);
        transform-origin: center;
    }
    .dark .hamburger span {
        background: white;
    }
    .hamburger.open span:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
    }
    .hamburger.open span:nth-child(2) {
        opacity: 0;
        transform: scaleX(0);
    }
    .hamburger.open span:nth-child(3) {
        transform: translateY(-7px) rotate(-45deg);
    }

    /* Mobile menu */
    .mobile-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.5);
        backdrop-filter: blur(4px);
        z-index: 199;
        opacity: 0;
        transition: opacity .3s ease;
        pointer-events: none;
    }
    .mobile-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .mobile-menu {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: min(320px, 88vw);
        background: white;
        z-index: 200;
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform .35s cubic-bezier(.4,0,.2,1);
        box-shadow: -20px 0 60px rgba(0,0,0,.15);
    }
    .dark .mobile-menu {
        background: #0A1229;
    }
    .mobile-menu.active {
        transform: translateX(0);
    }

    .mobile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F1F5F9;
    }
    .dark .mobile-header {
        border-color: rgba(255,255,255,.08);
    }

    .mobile-body {
        flex: 1;
        overflow-y: auto;
        padding: .75rem;
        display: flex;
        flex-direction: column;
        gap: .2rem;
    }
    .mobile-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .875rem 1rem;
        border-radius: 10px;
        font-size: .92rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: all .2s ease;
    }
    .dark .mobile-link {
        color: rgba(255,255,255,.85);
    }
    .mobile-link:hover, .mobile-link.active {
        background: #EFF6FF;
        color: #1A56DB;
    }
    .dark .mobile-link:hover, .dark .mobile-link.active {
        background: rgba(255,255,255,.08);
        color: white;
    }
    .mobile-link.active {
        font-weight: 800;
    }
    .mobile-sublink {
        display: block;
        padding: .5rem 1rem;
        margin-left: .5rem;
        border-radius: 8px;
        font-size: .84rem;
        font-weight: 500;
        color: #64748B;
        text-decoration: none;
        transition: all .2s;
    }
    .dark .mobile-sublink {
        color: rgba(255,255,255,.55);
    }
    .mobile-sublink:hover {
        background: #F8FAFF;
        color: #1A56DB;
    }
    .dark .mobile-sublink:hover {
        background: rgba(255,255,255,.06);
        color: white;
    }

    .mobile-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #F1F5F9;
    }
    .dark .mobile-footer {
        border-color: rgba(255,255,255,.08);
    }

    /* Theme toggle */
    .theme-btn {
        background: #F1F5F9;
        border: none;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        transition: all .25s ease;
    }
    .theme-btn:hover {
        background: #E2E8F0;
        transform: scale(1.08);
    }
    .dark .theme-btn {
        background: rgba(255,255,255,.1);
    }
    .dark .theme-btn:hover {
        background: rgba(255,255,255,.18);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .nav-menu {
            display: none;
        }
        .hamburger {
            display: flex;
        }
        .mobile-overlay {
            display: block;
        }
    }
    @media (max-width: 768px) {
        .topbar {
            display: none;
        }
    }
</style>

<!-- Topbar -->
<div class="topbar" id="topbar">
    <div class="container">
        <div class="topbar-inner">
            <div class="topbar-left">
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                </span>
                @if($profil && $profil->telepon)
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    {{ $profil->telepon }}
                </span>
                @endif
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    {{ $profil->email ?? 'diskominfo@sanggau.go.id' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo">
                <div style="position: relative;">
                    <img src="{{ asset('images/logo-sanggau.png') }}" 
                         alt="Logo Kabupaten Sanggau" 
                         class="nav-logo-img"
                         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Lambang_Kabupaten_Sanggau.png/600px-Lambang_Kabupaten_Sanggau.png'">
                    <div style="position: absolute; inset: -3px; border-radius: 50%; border: 1.5px solid rgba(26,86,219,.2); pointer-events: none;"></div>
                </div>
                <div class="nav-logo-text">
                    <div class="nav-logo-title">Dinas Komunikasi dan Informatika</div>
                    <div class="nav-logo-sub">Kabupaten Sanggau</div>
                </div>
            </a>

            <!-- Desktop menu -->
            <ul class="nav-menu">
                @foreach($navItems as $item)
                <li>
                    <a href="{{ $item['url'] }}" class="{{ request()->is(trim(parse_url($item['url'], PHP_URL_PATH), '/')) ? 'active' : '' }}">
                        {{ $item['label'] }}
                        @if(isset($item['children']))
                        <span class="nav-chevron">▾</span>
                        @endif
                    </a>
                    @if(isset($item['children']))
                    <div class="nav-dropdown">
                        @foreach($item['children'] as $child)
                        <a href="{{ $child['url'] }}">
                            <span style="color: #1A56DB;">›</span>
                            {{ $child['label'] }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </li>
                @endforeach
            </ul>

            <!-- Controls -->
            <div style="display: flex; align-items: center; gap: .75rem;">
                <button class="theme-btn" onclick="toggleDarkMode()" aria-label="Toggle Dark Mode">
                    <span class="theme-icon">🌙</span>
                </button>
                <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <!-- Header -->
    <div class="mobile-header">
        <div style="display: flex; align-items: center; gap: .75rem;">
            <img src="{{ asset('images/logo-sanggau.png') }}" 
                 alt="Logo" 
                 style="width: 36px; height: 36px; object-fit: contain;"
                 onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Lambang_Kabupaten_Sanggau.png/600px-Lambang_Kabupaten_Sanggau.png'">
            <div>
                <div style="font-weight: 800; font-size: .9rem; color: #0F172A;" class="mobile-title">Diskominfo</div>
                <div style="font-size: .65rem; color: #1A56DB; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;">Kab. Sanggau</div>
            </div>
        </div>
        <button id="closeMobileMenu" aria-label="Tutup menu"
                style="background: #F1F5F9; border: none; border-radius: 50%; width: 36px; height: 36px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748B;">
            ✕
        </button>
    </div>

    <!-- Body -->
    <div class="mobile-body">
        @foreach($navItems as $item)
        <div>
            <a href="{{ $item['url'] }}" class="mobile-link {{ request()->is(trim(parse_url($item['url'], PHP_URL_PATH), '/')) ? 'active' : '' }}">
                <span>{{ $item['label'] }}</span>
                @if(isset($item['children']))
                <span style="font-size: .55rem; opacity: .5;">▾</span>
                @endif
            </a>
            @if(isset($item['children']))
            <div style="padding-left: .75rem; display: flex; flex-direction: column; gap: .15rem; margin-bottom: .25rem;">
                @foreach($item['children'] as $child)
                <a href="{{ $child['url'] }}" class="mobile-sublink">
                    <span style="color: #1A56DB; margin-right: .35rem;">›</span>{{ $child['label'] }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="mobile-footer">
        <button id="closeMobileMenuBtn"
                style="width: 100%; padding: .75rem; border-radius: 10px; border: 1px solid #E2E8F0; background: transparent; cursor: pointer; font-weight: 700; font-size: .88rem; color: #374151;">
            Tutup Menu
        </button>
    </div>
</div>

<script>
    // Navbar scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        const topbar = document.getElementById('topbar');
        
        if (window.scrollY > 20) {
            navbar?.classList.add('scrolled');
        } else {
            navbar?.classList.remove('scrolled');
        }
        
        // Hide topbar on scroll down
        if (window.scrollY > lastScroll && window.scrollY > 20) {
            topbar?.classList.add('hidden');
        } else {
            topbar?.classList.remove('hidden');
        }
        lastScroll = window.scrollY;
    }, { passive: true });
    
    // Mobile menu toggle
    const hamburger = document.getElementById('hamburger');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const closeMobileMenuBtn = document.getElementById('closeMobileMenuBtn');
    
    function toggleMobileMenu() {
        hamburger?.classList.toggle('open');
        mobileOverlay?.classList.toggle('active');
        mobileMenu?.classList.toggle('active');
        document.body.style.overflow = mobileMenu?.classList.contains('active') ? 'hidden' : '';
    }
    
    hamburger?.addEventListener('click', toggleMobileMenu);
    mobileOverlay?.addEventListener('click', toggleMobileMenu);
    closeMobileMenu?.addEventListener('click', toggleMobileMenu);
    closeMobileMenuBtn?.addEventListener('click', toggleMobileMenu);
    
    // Theme toggle icon update
    function updateThemeIcon() {
        const icon = document.querySelector('.theme-icon');
        if (icon) {
            icon.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
        }
    }
    updateThemeIcon();
</script>
