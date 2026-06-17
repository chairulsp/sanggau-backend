<?php
/**
 * fix-pegawai-foto.php
 * Akses sekali via browser: https://api.diskominfo.sanggau.go.id/fix-pegawai-foto.php?key=diskominfo2024
 * HAPUS file ini setelah dijalankan!
 */

// Kunci pengaman agar tidak sembarangan diakses
$SECRET_KEY = 'diskominfo2024';
if (($_GET['key'] ?? '') !== $SECRET_KEY) {
    http_response_code(403);
    die('403 Forbidden');
}

// Bootstrap Laravel
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$db = app('db');

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';
echo "=== FIX FOTO PEGAWAI ===\n\n";

// ── LANGKAH 1: Pull git terbaru ──────────────────────────────────
echo "[ STEP 1 ] Git pull...\n";
$laravelRoot = dirname(__DIR__);
$gitOutput = shell_exec("cd {$laravelRoot} && git pull origin main 2>&1");
if ($gitOutput) {
    echo htmlspecialchars($gitOutput) . "\n";
} else {
    echo "shell_exec tidak tersedia, skip git pull.\n";
    echo "Pastikan Anda sudah upload file PegawaiController.php terbaru ke cPanel.\n";
}
echo "\n";

// ── LANGKAH 2: Buat folder public/uploads/pegawai jika belum ada ──
echo "[ STEP 2 ] Membuat folder public/uploads/pegawai...\n";
$uploadsDir = __DIR__ . '/uploads/pegawai';
if (!is_dir($uploadsDir)) {
    if (mkdir($uploadsDir, 0755, true)) {
        echo "Folder dibuat: {$uploadsDir}\n";
    } else {
        echo "GAGAL membuat folder! Buat manual via File Manager cPanel.\n";
    }
} else {
    echo "Folder sudah ada.\n";
}
echo "\n";

// ── LANGKAH 3: Pindahkan file dari storage ke uploads ────────────
echo "[ STEP 3 ] Memindahkan foto dari storage/app/public/pegawai ke public/uploads/pegawai...\n";
$storageDir = dirname(__DIR__) . '/storage/app/public/pegawai';
$moved = 0;
$skipped = 0;
$failed = 0;

if (is_dir($storageDir)) {
    $files = scandir($storageDir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $src  = $storageDir . '/' . $file;
        $dest = $uploadsDir . '/' . $file;
        if (file_exists($dest)) {
            echo "  SKIP (sudah ada): {$file}\n";
            $skipped++;
            continue;
        }
        if (copy($src, $dest)) {
            echo "  OK: {$file}\n";
            $moved++;
        } else {
            echo "  GAGAL: {$file}\n";
            $failed++;
        }
    }
    echo "\nDipindahkan: {$moved} | Skip: {$skipped} | Gagal: {$failed}\n";
} else {
    echo "Folder storage tidak ditemukan: {$storageDir}\n";
    echo "Skip langkah ini.\n";
}
echo "\n";

// ── LANGKAH 4: Update path di database ──────────────────────────
echo "[ STEP 4 ] Update path foto di database...\n";
try {
    $pegawaiList = $db->table('pegawai')
        ->whereNotNull('foto')
        ->where('foto', 'like', '/storage/pegawai/%')
        ->get();

    $updatedCount = 0;
    foreach ($pegawaiList as $p) {
        $newPath = str_replace('/storage/pegawai/', '/uploads/pegawai/', $p->foto);
        $db->table('pegawai')->where('id', $p->id)->update(['foto' => $newPath]);
        echo "  Updated [{$p->id}] {$p->nama_lengkap}: {$p->foto} → {$newPath}\n";
        $updatedCount++;
    }

    if ($updatedCount === 0) {
        echo "  Tidak ada data yang perlu diupdate (mungkin sudah benar atau belum ada foto).\n";
    } else {
        echo "\nTotal diupdate: {$updatedCount} pegawai.\n";
    }
} catch (Exception $e) {
    echo "ERROR database: " . htmlspecialchars($e->getMessage()) . "\n";
}
echo "\n";

// ── SELESAI ──────────────────────────────────────────────────────
echo "=== SELESAI ===\n";
echo "\nSEGERA HAPUS file ini dari cPanel File Manager setelah selesai!\n";
echo "Path: public/fix-pegawai-foto.php\n";
echo '</pre>';
