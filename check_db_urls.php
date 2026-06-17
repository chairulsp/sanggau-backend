<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Database URL Checker</h2><pre>\n";

$laravelDir = realpath(__DIR__ . '/../laravel');

try {
    require $laravelDir . '/vendor/autoload.php';
    $app = require_once $laravelDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Check banners
    echo "=== 1. Checking Banners Table (Raw Database Values) ===\n";
    $banners = \Illuminate\Support\Facades\DB::table('banners')->get();
    foreach ($banners as $b) {
        echo "ID: {$b->id} | Judul: {$b->judul}\n";
        echo "  - Raw gambar: " . ($b->gambar ?? 'NULL') . "\n";
    }
    
    // Check berita
    echo "\n=== 2. Checking Berita Table (Raw Database Values) ===\n";
    $berita = \Illuminate\Support\Facades\DB::table('berita')->get();
    foreach ($berita as $n) {
        echo "ID: {$n->id} | Judul: {$n->judul}\n";
        echo "  - Raw gambar: " . ($n->gambar ?? 'NULL') . "\n";
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
