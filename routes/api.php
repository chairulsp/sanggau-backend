<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\Api\Admin\Coverage4gController;
use App\Http\Controllers\Api\Admin\ProfileController;

// ===================== VISITOR TRACKING =====================
Route::post('/track', [VisitorController::class, 'track']);

// ===================== PUBLIC ROUTES =====================
Route::get('/berita',               [PublicController::class, 'berita']);
Route::get('/hoaks',                [PublicController::class, 'hoaks']);
Route::get('/berita/{slug}',        [PublicController::class, 'detailBerita']);
Route::get('/agenda',               [PublicController::class, 'agenda']);
Route::get('/layanan',              [PublicController::class, 'layanan']);
Route::get('/skpd',                 [PublicController::class, 'skpd']);
Route::get('/banner',               [PublicController::class, 'banner']);
Route::get('/pengumuman',           [PublicController::class, 'pengumuman']);
Route::get('/statistik',            [PublicController::class, 'statistik']);
Route::get('/galeri',               [PublicController::class, 'galeri']);
Route::get('/profil-diskominfo',    [PublicController::class, 'profilDiskominfo']);
Route::get('/struktur-organisasi',  [PublicController::class, 'strukturOrganisasi']);
Route::get('/dokumen',              [PublicController::class, 'dokumen']);
Route::get('/ppid',                 [PublicController::class, 'ppid']);
Route::get('/video',                [PublicController::class, 'video']);
Route::post('/pengaduan',           [PublicController::class, 'pengaduan']);
Route::get('/laman',                [PublicController::class, 'semuaLaman']);
Route::get('/laman/{slug}',         [PublicController::class, 'laman']);
Route::get('/settings',             [PublicController::class, 'settings']);

// Pegawai & Pimpinan (PUBLIC) - Taruh di sini sebelum middleware auth
Route::get('/pegawai',              [PegawaiController::class, 'index']);
Route::get('/profil-pimpinan',      [PegawaiController::class, 'profilPimpinan']);

Route::get('/menu', function () {
    return response()->json(\App\Models\Menu::where('aktif', true)->orderBy('urutan')->get());
});

// Coverage 4G (public - untuk homepage)
Route::get('/coverage4g', function () {
    return response()->json(\App\Models\Coverage4g::orderBy('urutan')->get());
});

// ===================== AUTH =====================
Route::post('/login',  [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ===================== ADMIN ROUTES =====================
Route::middleware('auth:sanctum')->prefix('admin')->name('admin.')->group(function () {

    // Profile (semua role bisa akses)
    Route::put('profile',                  [ProfileController::class, 'update']);
    Route::post('profile/change-password', [ProfileController::class, 'changePassword']);

    // Konten (Berita dan Dokumen bisa admin, penulis, dan editor)
    Route::middleware('role:admin,penulis,editor')->group(function () {
        Route::apiResource('berita',      \App\Http\Controllers\Api\Admin\BeritaController::class);
        Route::post('berita/upload-image', [\App\Http\Controllers\Api\Admin\BeritaController::class, 'uploadImage']);
        Route::apiResource('dokumen',     \App\Http\Controllers\Api\Admin\DokumenController::class);
    });

    // Konten & Manajemen (Admin & Superadmin - kecuali Pengguna)
    Route::middleware('role:admin')->group(function () {
        // Konten Management
        Route::apiResource('pengumuman',  \App\Http\Controllers\Api\Admin\PengumumanController::class);
        Route::apiResource('galeri',      \App\Http\Controllers\Api\Admin\GaleriController::class);
        Route::apiResource('agenda',      \App\Http\Controllers\Api\Admin\AgendaController::class);
        Route::apiResource('banner',      \App\Http\Controllers\Api\Admin\BannerController::class);
        Route::apiResource('video',       \App\Http\Controllers\Api\Admin\VideoController::class);
        Route::apiResource('laman',       \App\Http\Controllers\Api\Admin\LamanController::class);
        
        // Pegawai Management
        Route::get('/pegawai',         [PegawaiController::class, 'adminIndex']);
        Route::post('/pegawai',        [PegawaiController::class, 'store']);
        Route::get('/pegawai/{id}',    [PegawaiController::class, 'show']);
        Route::put('/pegawai/{id}',    [PegawaiController::class, 'update']);
        Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);

        // Layanan & Data Management
        Route::apiResource('layanan',   \App\Http\Controllers\Api\Admin\LayananController::class);
        Route::post('layanan/sync',     [\App\Http\Controllers\Api\Admin\LayananController::class, 'sync']);
        Route::patch('layanan/{layanan}/toggle-active', [\App\Http\Controllers\Api\Admin\LayananController::class, 'toggleActive']);
        Route::apiResource('statistik', \App\Http\Controllers\Api\Admin\StatistikController::class);
        Route::apiResource('skpd',      \App\Http\Controllers\Api\Admin\SkpdController::class);
        Route::apiResource('menu',      \App\Http\Controllers\Api\Admin\MenuController::class);
        Route::apiResource('struktur',  \App\Http\Controllers\Api\Admin\StrukturOrganisasiController::class);
        Route::apiResource('ppid',      \App\Http\Controllers\Api\Admin\PpidController::class);

        // Settings Management
        Route::post('settings/bulk',    [\App\Http\Controllers\Api\Admin\SettingController::class, 'bulkUpdate']);
        Route::apiResource('settings',  \App\Http\Controllers\Api\Admin\SettingController::class);

        // Profil Diskominfo
        Route::get('profil-diskominfo',  [\App\Http\Controllers\Api\Admin\ProfilDiskominfoController::class, 'show']);
        Route::post('profil-diskominfo', [\App\Http\Controllers\Api\Admin\ProfilDiskominfoController::class, 'update']);
        Route::put('profil-diskominfo',  [\App\Http\Controllers\Api\Admin\ProfilDiskominfoController::class, 'update']);

        // Pengaduan Management
        Route::get('pengaduan',             [\App\Http\Controllers\Api\Admin\PengaduanAdminController::class, 'index']);
        Route::get('pengaduan/{id}',         [\App\Http\Controllers\Api\Admin\PengaduanAdminController::class, 'show']);
        Route::post('pengaduan/{id}/balas',  [\App\Http\Controllers\Api\Admin\PengaduanAdminController::class, 'balas']);
        Route::put('pengaduan/{id}/status',  [\App\Http\Controllers\Api\Admin\PengaduanAdminController::class, 'updateStatus']);
        Route::delete('pengaduan/{id}',      [\App\Http\Controllers\Api\Admin\PengaduanAdminController::class, 'destroy']);

        // Coverage 4G
        Route::get('coverage4g',            [Coverage4gController::class, 'index']);
        Route::put('coverage4g/{coverage4g}', [Coverage4gController::class, 'update']);
        Route::post('coverage4g/bulk',      [Coverage4gController::class, 'bulk']);

        // Visitor stats & Login history (read only)
        Route::get('visitor-stats', [VisitorController::class, 'stats']);
        Route::get('login-history', [\App\Http\Controllers\Api\Admin\LoginHistoryController::class, 'index']);
    });

    // Superadmin Only - Manajemen Pengguna & Delete Login History
    Route::middleware('role:superadmin')->group(function () {
        // User Management (ONLY Superadmin)
        Route::apiResource('pengguna',  \App\Http\Controllers\Api\Admin\UserController::class);
        
        // Delete Login History (ONLY Superadmin)
        Route::delete('login-history/{login_history}', [\App\Http\Controllers\Api\Admin\LoginHistoryController::class, 'destroy']);
    });
});