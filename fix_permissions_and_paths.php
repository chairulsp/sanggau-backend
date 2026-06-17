<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Permissions and Paths Fixer</h2><pre>\n";

$publicHtmlDir = __DIR__;
$laravelDir = realpath(__DIR__ . '/../laravel');

echo "Public HTML Dir: $publicHtmlDir\n";
echo "Laravel Dir:     $laravelDir\n\n";

// 1. Try to set permissions recursively
echo "=== 1. Setting permissions for uploads folders ===\n";
$dirs = [
    $publicHtmlDir . '/uploads',
    $publicHtmlDir . '/uploads/berita',
    $publicHtmlDir . '/uploads/banner',
    $publicHtmlDir . '/uploads/galeri',
    $publicHtmlDir . '/uploads/settings'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        echo "Creating directory: $dir\n";
        mkdir($dir, 0775, true);
    }
    
    // Try chmod to 0775
    if (chmod($dir, 0777)) {
        echo "✅ Chmod 0777 success for: $dir\n";
    } else {
        echo "❌ Chmod failed for: $dir. Trying 0755...\n";
        if (chmod($dir, 0755)) {
            echo "✅ Chmod 0755 success for: $dir\n";
        } else {
            echo "❌ Chmod 0755 also failed.\n";
        }
    }
}

// 2. Clear Laravel configuration/bootstrap cache
echo "\n=== 2. Clearing Laravel config/services cache if they exist ===\n";
$cacheFiles = [
    $laravelDir . '/bootstrap/cache/config.php',
    $laravelDir . '/bootstrap/cache/services.php',
    $laravelDir . '/bootstrap/cache/packages.php'
];
foreach ($cacheFiles as $cacheFile) {
    if (file_exists($cacheFile)) {
        if (unlink($cacheFile)) {
            echo "✅ Deleted cache file: " . basename($cacheFile) . "\n";
        } else {
            echo "❌ Failed to delete cache file: " . basename($cacheFile) . "\n";
        }
    }
}

// 3. Boot Laravel to check path.public
echo "\n=== 3. Booting Laravel to verify public_path() ===\n";
try {
    require $laravelDir . '/vendor/autoload.php';
    $app = require_once $laravelDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "base_path():   " . base_path() . "\n";
    echo "public_path(): " . public_path() . "\n";
    
    $bannerUploadPath = public_path('uploads/banner');
    $beritaUploadPath = public_path('uploads/berita');
    
    echo "Banner upload path: $bannerUploadPath\n";
    echo "Berita upload path: $beritaUploadPath\n";
    
    // Test write permission by writing a dummy file
    echo "\n=== 4. Testing write permission with test file ===\n";
    $testBannerFile = $bannerUploadPath . '/test_write.txt';
    $testBeritaFile = $beritaUploadPath . '/test_write.txt';
    
    if (@file_put_contents($testBannerFile, 'test') !== false) {
        echo "✅ Banner directory is WRITABLE! (Successfully wrote test_write.txt)\n";
        @unlink($testBannerFile);
    } else {
        echo "❌ Banner directory is NOT writable by Laravel!\n";
    }
    
    if (@file_put_contents($testBeritaFile, 'test') !== false) {
        echo "✅ Berita directory is WRITABLE! (Successfully wrote test_write.txt)\n";
        @unlink($testBeritaFile);
    } else {
        echo "❌ Berita directory is NOT writable by Laravel!\n";
    }
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n</pre>";
