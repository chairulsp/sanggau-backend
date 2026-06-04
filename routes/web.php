<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\BeritaController;
use App\Http\Controllers\Web\GaleriController;
use App\Http\Controllers\Web\ProfilController;
use App\Http\Controllers\Web\LayananController;
use App\Http\Controllers\Web\AgendaController;
use App\Http\Controllers\Web\PengumumanController;
use App\Http\Controllers\Web\PpidController;
use App\Http\Controllers\Web\DownloadController;
use App\Http\Controllers\Web\KontakController;
use App\Http\Controllers\Web\PengaduanController;
use App\Http\Controllers\Web\LamanController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;

// ─── Test Route (No Database) ────────────────────────────────────────────────
Route::get('/test', function() {
    return view('test');
})->name('test');

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/',                [HomeController::class,       'index'])->name('home');
Route::get('/berita',          [BeritaController::class,     'index'])->name('berita');
Route::get('/berita/{slug}',   [BeritaController::class,     'show'])->name('berita.show');
Route::get('/galeri',          [GaleriController::class,     'index'])->name('galeri');
Route::get('/profil',          [ProfilController::class,     'index'])->name('profil');
Route::get('/layanan',         [LayananController::class,    'index'])->name('layanan');
Route::get('/agenda',          [AgendaController::class,     'index'])->name('agenda');
Route::get('/pengumuman',      [PengumumanController::class, 'index'])->name('pengumuman');
Route::get('/ppid',            [PpidController::class,       'index'])->name('ppid');
Route::get('/download',        [DownloadController::class,   'index'])->name('download');
Route::get('/kontak',          [KontakController::class,     'index'])->name('kontak');
Route::get('/pengaduan',       [PengaduanController::class,  'index'])->name('pengaduan.index');
Route::post('/pengaduan',      [PengaduanController::class,  'store'])->name('pengaduan.store');
Route::get('/laman/{slug}',    [LamanController::class,      'show'])->name('laman.show');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
// Admin Authentication (Public - No Auth Required)
Route::get('/admin/login',     [AdminAuthController::class,  'showLoginForm'])->name('admin.login');
Route::post('/admin/login',    [AdminAuthController::class,  'login'])->name('admin.login.post');

// Admin Panel (Protected - Auth Required)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Logout
    Route::post('/logout',          [AdminAuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');
    
    // Berita Management
    Route::resource('berita', AdminBeritaController::class);
    Route::post('berita/{id}/toggle-status', [AdminBeritaController::class, 'toggleStatus'])->name('berita.toggle');
});
