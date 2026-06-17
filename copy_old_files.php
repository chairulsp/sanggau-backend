<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🚚 File Copier & Permission Checker</h2><pre>\n";

$publicHtmlDir = __DIR__;
$srcDir = $publicHtmlDir . '/uploads_old';
$destDir = $publicHtmlDir . '/uploads';

echo "Source:      $srcDir\n";
echo "Destination: $destDir\n\n";

if (!is_dir($srcDir)) {
    die("❌ Source directory $srcDir does not exist.\n");
}

// 1. Copy files recursively
echo "=== 1. Copying files from uploads_old to uploads ===\n";
copy_recursive($srcDir, $destDir);

// 2. Verify files in destination
echo "\n=== 2. Verifying files in destination ===\n";
verify_destination($destDir);

// 3. Test writing a new test file via Laravel
echo "\n=== 3. Testing if Laravel can write to these folders ===\n";
$laravelDir = realpath(__DIR__ . '/../laravel');
try {
    require $laravelDir . '/vendor/autoload.php';
    $app = require_once $laravelDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $bannerUploadPath = public_path('uploads/banner');
    $beritaUploadPath = public_path('uploads/berita');
    
    echo "public_path('uploads/banner'): $bannerUploadPath\n";
    echo "public_path('uploads/berita'): $beritaUploadPath\n";
    
    $testBannerFile = $bannerUploadPath . '/laravel_test.txt';
    $testBeritaFile = $beritaUploadPath . '/laravel_test.txt';
    
    if (@file_put_contents($testBannerFile, 'laravel-write-test') !== false) {
        echo "✅ Laravel CAN write to uploads/banner!\n";
        @unlink($testBannerFile);
    } else {
        echo "❌ Laravel CANNOT write to uploads/banner!\n";
    }
    
    if (@file_put_contents($testBeritaFile, 'laravel-write-test') !== false) {
        echo "✅ Laravel CAN write to uploads/berita!\n";
        @unlink($testBeritaFile);
    } else {
        echo "❌ Laravel CANNOT write to uploads/berita!\n";
    }
} catch (Throwable $e) {
    echo "Error booting Laravel: " . $e->getMessage() . "\n";
}

function copy_recursive($src, $dest) {
    if (!is_dir($dest)) {
        mkdir($dest, 0775, true);
    }
    
    $items = scandir($src);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $srcPath = $src . '/' . $item;
        $destPath = $dest . '/' . $item;
        
        if (is_dir($srcPath)) {
            copy_recursive($srcPath, $destPath);
        } else {
            if (copy($srcPath, $destPath)) {
                echo "✅ Copied: $item to " . basename(dirname($destPath)) . "/\n";
            } else {
                echo "❌ Failed to copy: $item\n";
            }
        }
    }
}

function verify_destination($dir) {
    if (!is_dir($dir)) {
        echo "Destination $dir does not exist.\n";
        return;
    }
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            echo "Folder: " . basename($dir) . "/$item/\n";
            $subItems = scandir($path);
            foreach ($subItems as $subItem) {
                if ($subItem === '.' || $subItem === '..') continue;
                echo "  - $subItem (" . filesize($path . '/' . $subItem) . " bytes)\n";
            }
        }
    }
}

echo "\n</pre>";
