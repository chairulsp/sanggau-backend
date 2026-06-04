<?php
/**
 * Check Laravel Error Log
 * Upload ke: public_html/check-error.php
 * Akses: https://diskominfo.sanggau.go.id/check-error.php
 * HAPUS setelah selesai!
 */

echo "<h2>Laravel Error Checker</h2>";

// 1. Cek PHP version
echo "<h3>PHP Version</h3>";
echo PHP_VERSION . "<br>";

// 2. Cek apakah vendor ada
echo "<h3>Vendor Check</h3>";
$vendorPath = __DIR__ . '/vendor/autoload.php';
echo "Vendor path: " . $vendorPath . "<br>";
echo "Vendor exists: " . (file_exists($vendorPath) ? "✅ YES" : "❌ NO") . "<br>";

// 3. Cek .env
echo "<h3>.env Check</h3>";
$envPath = __DIR__ . '/.env';
echo ".env exists: " . (file_exists($envPath) ? "✅ YES" : "❌ NO") . "<br>";

// 4. Cek storage/logs
echo "<h3>Laravel Error Log (last 50 lines)</h3>";
$logPath = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logPath)) {
    $lines = file($logPath);
    $last50 = array_slice($lines, -50);
    echo "<pre style='background:#1e1e1e;color:#f8f8f2;padding:1rem;border-radius:8px;overflow:auto;max-height:500px;font-size:12px'>";
    foreach ($last50 as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "❌ Log file tidak ditemukan di: " . $logPath . "<br>";
    
    // Coba cari log di tempat lain
    $altLog = __DIR__ . '/../laravel/storage/logs/laravel.log';
    if (file_exists($altLog)) {
        echo "✅ Log ditemukan di: " . $altLog . "<br>";
        $lines = file($altLog);
        $last50 = array_slice($lines, -50);
        echo "<pre style='background:#1e1e1e;color:#f8f8f2;padding:1rem;border-radius:8px;overflow:auto;max-height:500px;font-size:12px'>";
        foreach ($last50 as $line) {
            echo htmlspecialchars($line);
        }
        echo "</pre>";
    }
}

// 5. Cek struktur folder
echo "<h3>Folder Structure</h3>";
$dirs = ['app', 'bootstrap', 'config', 'database', 'public', 'routes', 'storage', 'vendor'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    echo $dir . ": " . (is_dir($path) ? "✅ EXISTS" : "❌ MISSING") . "<br>";
}

// 6. Cek storage permissions
echo "<h3>Storage Permissions</h3>";
$storageDirs = [
    'storage/app',
    'storage/app/public', 
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];
foreach ($storageDirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        $writable = is_writable($path);
        echo $dir . ": " . ($writable ? "✅ Writable" : "❌ NOT Writable") . "<br>";
    } else {
        echo $dir . ": ❌ NOT EXISTS<br>";
    }
}

echo "<hr><p style='color:red'><strong>⚠️ HAPUS file ini setelah selesai debugging!</strong></p>";
