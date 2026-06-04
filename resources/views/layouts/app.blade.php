<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Portal Resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau')">
    <meta name="keywords" content="diskominfo, sanggau, komunikasi, informatika, pemerintah">
    <title>@yield('title', 'Diskominfo Kabupaten Sanggau')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    
    <!-- Base Styles -->
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Plus Jakarta+Sans', sans-serif; 
            background: #FFFFFF;
            color: #0F172A;
            line-height: 1.6;
        }
        :root {
            --primary: #1A56DB;
            --primary-dark: #1D4ED8;
            --secondary: #F59E0B;
            --text-primary: #0F172A;
            --text-secondary: #374151;
            --text-muted: #64748B;
            --bg-surface: #FFFFFF;
            --border: #E2E8F0;
        }
        .dark {
            --text-primary: #F8FAFC;
            --text-secondary: rgba(255,255,255,0.85);
            --text-muted: rgba(255,255,255,0.55);
            --bg-surface: #0F172A;
            --border: rgba(255,255,255,0.08);
        }
        .dark body { background: #070E37; color: #F8FAFC; }
        
        .container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
        .section { padding: 5rem 0; }
        
        /* Utilities */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        
        /* Page Hero */
        .page-hero {
            background: linear-gradient(135deg, #0F2061 0%, #1A56DB 100%);
            padding: 4.5rem 0 3.5rem;
            position: relative;
            overflow: hidden;
        }
        .page-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v6h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }
        .page-hero-content { position: relative; z-index: 2; }
        .page-hero h1 {
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            font-weight: 900;
            color: white;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }
        .page-hero p {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.85);
            max-width: 640px;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s; }
        .breadcrumb a:hover { color: white; }
        
        /* Skeleton loader */
        .skeleton {
            background: linear-gradient(90deg, #E2E8F0 25%, #F1F5F9 50%, #E2E8F0 75%);
            background-size: 200% 100%;
            animation: skeleton 1.5s ease-in-out infinite;
            border-radius: 8px;
        }
        @keyframes skeleton {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 0 1rem; }
            .section { padding: 3rem 0; }
            .page-hero { padding: 3rem 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" class="scroll-top" aria-label="Kembali ke atas">
        ↑
    </button>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        // Scroll to top functionality
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        }, { passive: true });
        
        scrollTopBtn?.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Dark mode toggle (if needed)
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
        }
        
        // Check saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark');
        }
    </script>
    @stack('scripts')
    
    <style>
        .scroll-top {
            position: fixed;
            bottom: 2rem;
            right: 1.5rem;
            z-index: 98;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #1A56DB;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(26,86,219,0.35);
            opacity: 0;
            transform: translateY(12px);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            pointer-events: none;
        }
        .scroll-top.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .scroll-top:hover {
            background: #1D4ED8;
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(26,86,219,0.45);
        }
    </style>
</body>
</html>
