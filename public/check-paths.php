<?php
/**
 * check-paths.php
 * Upload ke /home/diskominfo/public_html/api/
 * Akses: https://api.diskominfo.sanggau.go.id/check-paths.php?key=diskominfo2024
 * HAPUS setelah selesai!
 */
if (($_GET['key'] ?? '') !== 'diskominfo2024') { http_response_code(403); die('403'); }

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo '<pre>';
echo "APP_URL      : " . config('app.url') . "\n";
echo "__DIR__      : " . __DIR__ . "\n";
echo "public_path(): " . public_path() . "\n";
echo "public_path('uploads/pegawai'): " . public_path('uploads/pegawai') . "\n";
echo "base_path()  : " . base_path() . "\n";
echo "storage_path(): " . storage_path() . "\n";
echo "\n";
echo "storage/app/public/pegawai exists: " . (is_dir(storage_path('app/public/pegawai')) ? 'YES' : 'NO') . "\n";
echo "public/uploads/pegawai exists    : " . (is_dir(public_path('uploads/pegawai')) ? 'YES' : 'NO') . "\n";
echo "\n";

// Cek file foto di storage
$storageDir = storage_path('app/public/pegawai');
if (is_dir($storageDir)) {
    $files = array_diff(scandir($storageDir), ['.', '..']);
    echo "Files in storage/app/public/pegawai (" . count($files) . "):\n";
    foreach (array_slice($files, 0, 5) as $f) {
        echo "  - $f\n";
    }
    if (count($files) > 5) echo "  ... dan " . (count($files) - 5) . " lainnya\n";
}
echo '</pre>';
