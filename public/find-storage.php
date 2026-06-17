<?php
/**
 * find-storage.php — cari lokasi file foto pegawai
 * Akses: https://api.diskominfo.sanggau.go.id/find-storage.php?key=diskominfo2024
 * HAPUS setelah selesai!
 */
if (($_GET['key'] ?? '') !== 'diskominfo2024') { http_response_code(403); die('403'); }

echo '<pre style="font-family:monospace;font-size:12px;background:#111;color:#0f0;padding:20px">';

echo "=== MENCARI FILE FOTO PEGAWAI ===\n\n";

// Cek dari posisi script ini
echo "__DIR__ (public_html): " . __DIR__ . "\n";
echo "dirname(__DIR__)      : " . dirname(__DIR__) . "\n\n";

// Kandidat path storage
$candidates = [
    __DIR__ . '/../laravel/storage/app/public/pegawai',
    __DIR__ . '/../../laravel/storage/app/public/pegawai',
    dirname(__DIR__) . '/laravel/storage/app/public/pegawai',
    '/home/diskominfo/laravel/storage/app/public/pegawai',
    '/home/diskominfo/public_html/../laravel/storage/app/public/pegawai',
];

echo "=== CEK KANDIDAT PATH ===\n";
foreach ($candidates as $path) {
    $real = realpath($path);
    if ($real && is_dir($real)) {
        $files = array_diff(scandir($real), ['.','..']);
        echo "✓ DITEMUKAN: $path\n";
        echo "  realpath : $real\n";
        echo "  Files    : " . count($files) . "\n";
        foreach (array_slice($files, 0, 3) as $f) {
            echo "  - $f\n";
        }
        echo "\n";
    } else {
        echo "✗ Tidak ada: $path\n";
    }
}

// Cari dari root home
echo "\n=== ISI /home/diskominfo/ ===\n";
$homeDir = '/home/diskominfo';
if (is_dir($homeDir)) {
    $items = array_diff(scandir($homeDir), ['.','..']);
    foreach ($items as $item) {
        $fullPath = $homeDir . '/' . $item;
        echo (is_dir($fullPath) ? '[DIR]  ' : '[FILE] ') . $item . "\n";
    }
}

// Cari folder bernama 'pegawai' dari beberapa level
echo "\n=== CARI FOLDER 'pegawai' ===\n";
$searchPaths = [
    '/home/diskominfo',
    dirname(__DIR__),
];
foreach ($searchPaths as $base) {
    $result = shell_exec("find $base -type d -name 'pegawai' 2>/dev/null");
    if ($result) {
        echo "Found in $base:\n$result\n";
    }
}

echo '</pre>';
