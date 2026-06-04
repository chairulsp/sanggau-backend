<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Diskominfo Sanggau</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --light: #f7fafc;
            --sidebar-width: 260px;
            --header-height: 70px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f7fafc;
            color: #2d3748;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .sidebar-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left h1 {
            font-size: 1.5rem;
            color: #2d3748;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: #2d3748;
            text-decoration: none;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover {
            background: #f7fafc;
        }

        /* Content */
        .content {
            padding: 30px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--secondary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #c6f6d5;
            color: #2f855a;
            border-left: 4px solid var(--secondary);
        }

        .alert-danger {
            background: #fed7d7;
            color: #c53030;
            border-left: 4px solid var(--danger);
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            background: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #c6f6d5;
            color: #2f855a;
        }

        .badge-danger {
            background: #fed7d7;
            color: #c53030;
        }

        .badge-warning {
            background: #feebc8;
            color: #c05621;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content {
                padding: 20px 15px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-sanggau.png') }}" alt="Logo" onerror="this.style.display='none'">
            <h3>Admin CMS</h3>
            <p style="font-size: 0.875rem; opacity: 0.8;">Diskominfo Sanggau</p>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i>📊</i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.berita.index') }}" class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <i>📰</i> Berita
                </a>
            </li>
            <li>
                <a href="#"><i>🎬</i> Galeri</a>
            </li>
            <li>
                <a href="#"><i>🎯</i> Banner</a>
            </li>
            <li>
                <a href="#"><i>⚙️</i> Layanan</a>
            </li>
            <li>
                <a href="#"><i>📅</i> Agenda</a>
            </li>
            <li>
                <a href="#"><i>📢</i> Pengumuman</a>
            </li>
            <li>
                <a href="#"><i>📄</i> PPID</a>
            </li>
            <li>
                <a href="#"><i>📥</i> Dokumen</a>
            </li>
            <li>
                <a href="#"><i>💬</i> Pengaduan</a>
            </li>
            <li>
                <a href="#"><i>🏛️</i> Profil Diskominfo</a>
            </li>
            <li>
                <a href="#"><i>👥</i> Pengguna</a>
            </li>
            <li>
                <a href="#"><i>⚙️</i> Pengaturan</a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <h1>@yield('title', 'Dashboard')</h1>
            </div>
            <div class="header-right">
                <div class="user-menu" onclick="toggleDropdown()">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.75rem; color: #718096;">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="#">👤 Profil Saya</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            🚪 Logout
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            @if (session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.user-menu')) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
