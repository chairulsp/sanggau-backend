# 🚀 QUICK REFERENCE GUIDE

Panduan cepat untuk mengoperasikan website Diskominfo Sanggau (Laravel Blade Version).

---

## ⚡ COMMANDS CHEAT SHEET

### Start Development Server
```bash
# Option 1: Laravel built-in server (Recommended for development)
php artisan serve
# Access: http://127.0.0.1:8000

# Option 2: XAMPP Apache (Access via http://localhost/sanggau-backend/public)
# Start XAMPP Control Panel → Start Apache
```

### Database Operations
```bash
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset and re-run all migrations (⚠️ Deletes all data!)
php artisan migrate:fresh

# Seed database with sample data
php artisan db:seed

# Fresh migrate + seed in one command
php artisan migrate:fresh --seed
```

### Cache Management
```bash
# Clear all caches (when something not working)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Storage & Assets
```bash
# Create symbolic link from public/storage to storage/app/public
php artisan storage:link

# Compile assets (development)
npm run dev

# Compile and minify assets (production)
npm run production

# Watch for changes (development)
npm run watch
```

### Maintenance Mode
```bash
# Enable maintenance mode
php artisan down

# Disable maintenance mode
php artisan up

# Enable with secret bypass
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"
# Access via: http://yoursite.com/1630542a-246b-4b66-afa1-dd72a4c43515
```

---

## 🗺️ PROJECT STRUCTURE

```
sanggau-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Web/              ← Public page controllers
│   │   │       ├── HomeController.php
│   │   │       ├── BeritaController.php
│   │   │       ├── GaleriController.php
│   │   │       └── ... (11 controllers)
│   │   └── Middleware/
│   └── Models/                   ← Eloquent models (20+ models)
│
├── database/
│   ├── migrations/               ← Database schema (29 files)
│   └── seeders/                  ← Sample data seeders
│
├── public/
│   ├── css/                      ← Compiled CSS
│   ├── js/                       ← Compiled JavaScript
│   ├── images/                   ← Static images (logo, etc)
│   └── storage/                  ← Symlink to storage/app/public
│
├── resources/
│   ├── views/
│   │   ├── layouts/              ← Master layouts
│   │   │   ├── app.blade.php
│   │   │   ├── navbar.blade.php
│   │   │   └── footer.blade.php
│   │   └── web/                  ← Public page views
│   │       ├── home.blade.php
│   │       ├── berita/
│   │       ├── galeri/
│   │       └── ... (11 page folders)
│   ├── css/                      ← Source CSS
│   └── js/                       ← Source JavaScript
│
├── routes/
│   ├── web.php                   ← Public routes (14 routes)
│   └── api.php                   ← API routes (optional)
│
├── storage/
│   ├── app/
│   │   └── public/               ← User uploaded files
│   └── logs/
│       └── laravel.log           ← Application logs
│
└── .env                          ← Environment configuration
```

---

## 🔗 ALL ROUTES

### Public Routes (No Authentication)
| Route | URL | Controller | Method | Description |
|-------|-----|------------|--------|-------------|
| `home` | `/` | HomeController | index | Homepage |
| `berita` | `/berita` | BeritaController | index | Berita list |
| `berita.show` | `/berita/{slug}` | BeritaController | show | Berita detail |
| `galeri` | `/galeri` | GaleriController | index | Galeri foto & video |
| `layanan` | `/layanan` | LayananController | index | Layanan publik |
| `profil` | `/profil` | ProfilController | index | Profil Diskominfo |
| `agenda` | `/agenda` | AgendaController | index | Agenda kegiatan |
| `pengumuman` | `/pengumuman` | PengumumanController | index | Pengumuman |
| `ppid` | `/ppid` | PpidController | index | Informasi PPID |
| `download` | `/download` | DownloadController | index | Download dokumen |
| `kontak` | `/kontak` | KontakController | index | Info kontak |
| `pengaduan.index` | `/pengaduan` | PengaduanController | index | Form pengaduan |
| `pengaduan.store` | `/pengaduan` | PengaduanController | store | Submit pengaduan |
| `laman.show` | `/laman/{slug}` | LamanController | show | Laman dinamis |

---

## 📁 MODELS & TABLES

| Model | Table | Description |
|-------|-------|-------------|
| `User` | `users` | System users |
| `Berita` | `beritas` | News articles |
| `Banner` | `banners` | Homepage slider |
| `Galeri` | `galeris` | Photo gallery |
| `GaleriVideo` | `galeri_videos` | Video gallery |
| `Layanan` | `layanans` | Public services |
| `Agenda` | `agendas` | Event calendar |
| `Pengumuman` | `pengumumans` | Announcements |
| `Ppid` | `ppids` | Public information |
| `Dokumen` | `dokumens` | Downloadable documents |
| `Pengaduan` | `pengaduans` | Public complaints |
| `ProfilDiskominfo` | `profil_diskominfos` | Organization profile |
| `ProfilKecamatan` | `profil_kecamatans` | District profiles |
| `Menu` | `menus` | Navigation menu |
| `Setting` | `settings` | Site settings |
| `Laman` | `lamans` | Custom pages |
| `LoginHistory` | `login_histories` | Login tracking |
| `Visitor` | `visitors` | Site visitors |
| `Pegawai` | `pegawais` | Employees |
| `Coverage4g` | `coverage_4gs` | 4G coverage data |

---

## 🎨 BLADE DIRECTIVES COMMONLY USED

```php
{{-- Comments (not rendered) --}}

{{ $variable }}                    <!-- Escaped output (safe) -->
{!! $html !!}                      <!-- Raw HTML output (use carefully!) -->

@if ($condition)
    <!-- content -->
@elseif ($other)
    <!-- other -->
@else
    <!-- default -->
@endif

@foreach ($items as $item)
    {{ $item->name }}
@endforeach

@forelse ($items as $item)
    {{ $item->name }}
@empty
    <p>No items found</p>
@endforelse

@for ($i = 0; $i < 10; $i++)
    {{ $i }}
@endfor

@while ($condition)
    <!-- content -->
@endwhile

@isset($variable)
    <!-- if variable is set -->
@endisset

@empty($variable)
    <!-- if variable is empty -->
@endempty

@auth
    <!-- if user is authenticated -->
@endauth

@guest
    <!-- if user is not authenticated -->
@endguest

@include('partials.header')       <!-- Include partial view -->
@yield('content')                  <!-- Define content section -->
@section('content')                <!-- Start section -->
@endsection                        <!-- End section -->

@extends('layouts.app')            <!-- Extend layout -->

{{ route('home') }}                <!-- Generate route URL -->
{{ asset('images/logo.png') }}     <!-- Generate asset URL -->
{{ url('/about') }}                <!-- Generate full URL -->

{{ $items->links() }}              <!-- Pagination links -->
```

---

## 💾 DATABASE QUERY EXAMPLES

```php
// ─── Select ───────────────────────────────────────────
// Get all records
$beritas = Berita::all();

// Get with where clause
$beritas = Berita::where('aktif', true)->get();

// Get single record
$berita = Berita::find(1);
$berita = Berita::where('slug', 'berita-1')->first();

// Get or fail (throws 404 if not found)
$berita = Berita::findOrFail(1);

// Select specific columns
$beritas = Berita::select('judul', 'slug', 'created_at')->get();

// ─── Where Clauses ────────────────────────────────────
Berita::where('aktif', true)->get();
Berita::where('views', '>', 100)->get();
Berita::where('kategori', 'teknologi')->get();
Berita::whereIn('kategori', ['teknologi', 'pendidikan'])->get();
Berita::whereBetween('created_at', ['2026-01-01', '2026-12-31'])->get();
Berita::whereNull('deleted_at')->get();

// Multiple where
Berita::where('aktif', true)
      ->where('kategori', 'teknologi')
      ->get();

// Or where
Berita::where('kategori', 'teknologi')
      ->orWhere('kategori', 'pendidikan')
      ->get();

// ─── Ordering ─────────────────────────────────────────
Berita::orderBy('created_at', 'desc')->get();
Berita::orderByDesc('views')->get();
Berita::latest()->get();           // orderBy('created_at', 'desc')
Berita::oldest()->get();           // orderBy('created_at', 'asc')

// ─── Pagination ───────────────────────────────────────
$beritas = Berita::paginate(12);                    // 12 per page
$beritas = Berita::where('aktif', true)->paginate(12);

// In view:
{{ $beritas->links() }}            // Show pagination

// ─── Insert ───────────────────────────────────────────
Berita::create([
    'judul' => 'Judul Berita',
    'slug' => 'judul-berita',
    'konten' => 'Isi berita...',
    'aktif' => true,
]);

// ─── Update ───────────────────────────────────────────
$berita = Berita::find(1);
$berita->judul = 'Judul Baru';
$berita->save();

// Or
Berita::where('id', 1)->update(['judul' => 'Judul Baru']);

// ─── Delete ───────────────────────────────────────────
$berita = Berita::find(1);
$berita->delete();

// Or
Berita::where('id', 1)->delete();
Berita::destroy(1);                // Delete by ID
Berita::destroy([1, 2, 3]);        // Delete multiple

// ─── Relationships ────────────────────────────────────
// Eager loading (prevent N+1 query problem)
$beritas = Berita::with('kategori')->get();

// ─── Aggregates ───────────────────────────────────────
Berita::count();
Berita::where('aktif', true)->count();
Berita::sum('views');
Berita::avg('views');
Berita::max('views');
Berita::min('views');
```

---

## 🔍 DEBUGGING COMMANDS

```bash
# Show all routes
php artisan route:list

# Show all routes for specific name
php artisan route:list --name=berita

# Clear error logs
echo "" > storage/logs/laravel.log

# Check if MySQL is running
netstat -ano | findstr :3306

# Test database connection (Laravel Tinker)
php artisan tinker
>>> DB::connection()->getPdo();

# Check model count
php artisan tinker
>>> App\Models\Berita::count()

# Check environment
php artisan env

# Show config value
php artisan tinker
>>> config('app.name')
>>> config('database.default')
```

---

## 🐛 COMMON ERRORS & SOLUTIONS

### Error: "500 Server Error"
**Solution**:
```bash
# Check Laravel log
type storage\logs\laravel.log

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check .env file is correct
# Check database connection
```

### Error: "Class not found"
**Solution**:
```bash
# Regenerate autoload files
composer dump-autoload
```

### Error: "View [name] not found"
**Solution**:
```bash
# Check view file exists in resources/views/
# Check view path in controller is correct
# Clear view cache
php artisan view:clear
```

### Error: "Route [name] not defined"
**Solution**:
```bash
# Check route name in routes/web.php
# Clear route cache
php artisan route:clear
# Check spelling in blade: route('name')
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Solution**:
```bash
# Check MySQL is running
# Check .env database credentials
# Check database exists
# Try: php artisan config:clear
```

### Error: "The stream or file ... could not be opened"
**Solution**:
```bash
# Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows (as Administrator)
icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
```

---

## 📱 TEST URLs (Development)

```
Homepage:       http://127.0.0.1:8000/
Berita:         http://127.0.0.1:8000/berita
Detail Berita:  http://127.0.0.1:8000/berita/berita-terbaru-1
Galeri:         http://127.0.0.1:8000/galeri
Layanan:        http://127.0.0.1:8000/layanan
Profil:         http://127.0.0.1:8000/profil
Agenda:         http://127.0.0.1:8000/agenda
Pengumuman:     http://127.0.0.1:8000/pengumuman
PPID:           http://127.0.0.1:8000/ppid
Download:       http://127.0.0.1:8000/download
Kontak:         http://127.0.0.1:8000/kontak
Pengaduan:      http://127.0.0.1:8000/pengaduan
```

---

## 🎯 QUICK TEST

```bash
# Start server
php artisan serve

# Open browser and test all pages
# Or use PowerShell to test all at once:
@('', 'berita', 'galeri', 'layanan', 'profil', 'pengaduan', 'kontak', 'agenda', 'download', 'ppid', 'pengumuman') | ForEach-Object { 
    $url = "http://127.0.0.1:8000/$_"
    $code = (curl -s -o NUL -w "%{http_code}" $url)
    Write-Host "$url : $code"
}

# Expected: All should show "200"
```

---

## 📚 DOCUMENTATION FILES

| File | Description |
|------|-------------|
| `README-LARAVEL-BLADE.md` | Main project documentation |
| `KONVERSI-SELESAI.md` | Complete conversion guide |
| `QUICK-START.md` | 5-step quick start guide |
| `START-HERE.md` | Master index with decision tree |
| `TROUBLESHOOTING-ERROR-500.md` | Troubleshooting MySQL errors |
| `STATUS-FINAL.md` | Final status report |
| `SESSION-SUMMARY.md` | Bug fixing session summary |
| `VERIFICATION-CHECKLIST.md` | QA testing checklist |
| `QUICK-REFERENCE.md` | This file (quick reference) |

---

## 🆘 NEED HELP?

### Check Documentation First
1. Read `START-HERE.md` - Master navigation
2. Check `QUICK-START.md` - Get started fast
3. Read `TROUBLESHOOTING-ERROR-500.md` - Common issues

### Still Stuck?
1. Check Laravel logs: `storage/logs/laravel.log`
2. Clear all cache: `php artisan cache:clear`
3. Check `.env` configuration
4. Verify database connection
5. Check browser console for JS errors

### Resources
- Laravel Docs: https://laravel.com/docs/8.x
- Laravel Blade: https://laravel.com/docs/8.x/blade
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel
- Laravel Forum: https://laracasts.com/discuss

---

**Quick Reference Version**: 1.0  
**Last Updated**: 3 Juni 2026  
**Prepared by**: Kiro AI Assistant
