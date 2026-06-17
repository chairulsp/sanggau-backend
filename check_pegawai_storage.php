<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "<h2>🔍 Pegawai Storage and DB Checker</h2><pre>\n";

$publicHtmlDir = __DIR__;
$laravelDir = realpath(__DIR__ . '/../laravel');

// 1. Check database for pegawai records
echo "=== 1. Checking Database 'pegawai' Table ===\n";
$envFile = $laravelDir . '/.env';
if (file_exists($envFile)) {
    $env = [];
    $lines = explode("\n", file_get_contents($envFile));
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $env[trim($parts[0])] = trim($parts[1], '"\' ');
        }
    }
    $dbHost = $env['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $env['DB_PORT'] ?? '3306';
    $dbName = $env['DB_DATABASE'] ?? '';
    $dbUser = $env['DB_USERNAME'] ?? '';
    $dbPass = $env['DB_PASSWORD'] ?? '';

    try {
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        $stmt = $pdo->query("SELECT id, nama_lengkap, foto FROM pegawai LIMIT 10");
        while ($row = $stmt->fetch()) {
            echo "ID: {$row['id']} | Nama: {$row['nama_lengkap']} | Foto: " . ($row['foto'] ?? 'NULL') . "\n";
        }
    } catch (Throwable $e) {
        echo "❌ DB Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ .env not found\n";
}

// 2. Check public_html/storage path
echo "\n=== 2. Checking public_html/storage path ===\n";
$storagePath = $publicHtmlDir . '/storage';
if (file_exists($storagePath) || is_link($storagePath)) {
    echo "Path: $storagePath\n";
    echo "  - Type: " . (is_link($storagePath) ? "LINK (Symlink)" : (is_dir($storagePath) ? "Directory" : "File")) . "\n";
    if (is_link($storagePath)) {
        echo "  - Target: " . readlink($storagePath) . "\n";
    }
    echo "  - Readable: " . (is_readable($storagePath) ? "YES" : "NO") . "\n";
    echo "  - Writable: " . (is_writable($storagePath) ? "YES" : "NO") . "\n";
} else {
    echo "❌ public_html/storage does not exist\n";
}

// 3. Check laravel/storage/app/public paths
echo "\n=== 3. Checking laravel/storage/app/public/pegawai path ===\n";
$laravelPublicStorage = $laravelDir . '/storage/app/public';
$laravelPegawaiStorage = $laravelPublicStorage . '/pegawai';

check_dir($laravelPublicStorage);
check_dir($laravelPegawaiStorage);

function check_dir($path) {
    if (is_dir($path)) {
        echo "Directory: $path (Writable: " . (is_writable($path) ? "YES" : "NO") . ")\n";
        $files = scandir($path);
        $count = 0;
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $count++;
            if ($count <= 10) {
                echo "  - $file\n";
            }
        }
        if ($count > 10) echo "  - ... and " . ($count - 10) . " more files\n";
        if ($count === 0) echo "  - (empty)\n";
    } else {
        echo "❌ Directory $path does not exist\n";
    }
}

echo "\n</pre>";
