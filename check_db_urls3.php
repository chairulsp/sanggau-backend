<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "<h2>🔍 Database URL Checker (v3 - Direct PDO)</h2><pre>\n";

$laravelDir = realpath(__DIR__ . '/../laravel');
$envFile = $laravelDir . '/.env';

if (!file_exists($envFile)) {
    die("❌ .env file not found at $envFile\n");
}

// Parse .env
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

echo "Connecting to Database: $dbName on $dbHost:$dbPort\n\n";

try {
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Check Banners
    echo "=== 1. Checking Banners Table ===\n";
    $stmt = $pdo->query("SELECT id, judul, gambar FROM banners");
    while ($row = $stmt->fetch()) {
        echo "ID: {$row['id']} | Judul: {$row['judul']}\n";
        echo "  - Raw gambar: " . ($row['gambar'] ?? 'NULL') . "\n";
    }
    
    // Check Berita
    echo "\n=== 2. Checking Berita Table ===\n";
    $stmt = $pdo->query("SELECT id, judul, gambar FROM berita ORDER BY id DESC LIMIT 5");
    while ($row = $stmt->fetch()) {
        echo "ID: {$row['id']} | Judul: {$row['judul']}\n";
        echo "  - Raw gambar: " . ($row['gambar'] ?? 'NULL') . "\n";
    }
    
} catch (Throwable $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
