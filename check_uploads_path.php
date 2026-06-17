<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Upload Path and Symlink Checker</h2><pre>\n";

$publicHtmlDir = __DIR__; // /home/diskominfo/public_html
$laravelDir = realpath(__DIR__ . '/../laravel'); // /home/diskominfo/laravel

echo "Public HTML Directory: $publicHtmlDir\n";
echo "Laravel Directory:     $laravelDir\n\n";

// 1. Boot Laravel to get public_path()
echo "=== 1. Booting Laravel to check path.public ===\n";
try {
    require $laravelDir . '/vendor/autoload.php';
    $app = require_once $laravelDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "base_path():   " . base_path() . "\n";
    echo "public_path(): " . public_path() . "\n";
    echo "public_path('uploads/banner'): " . public_path('uploads/banner') . "\n";
    echo "public_path('uploads/berita'): " . public_path('uploads/berita') . "\n";
} catch (Throwable $e) {
    echo "Error booting Laravel: " . $e->getMessage() . "\n";
}

// 2. Check Directory Structure & Symlinks in public_html
echo "\n=== 2. Checking public_html/uploads directories ===\n";
check_path($publicHtmlDir . '/uploads');
check_path($publicHtmlDir . '/uploads/berita');
check_path($publicHtmlDir . '/uploads/banner');

// 3. Check Directory Structure & Symlinks in laravel
echo "\n=== 3. Checking laravel/public/uploads or laravel/uploads directories ===\n";
check_path($laravelDir . '/uploads');
check_path($laravelDir . '/uploads/berita');
check_path($laravelDir . '/uploads/banner');
check_path($laravelDir . '/public/uploads');
check_path($laravelDir . '/public/uploads/berita');
check_path($laravelDir . '/public/uploads/banner');

// 4. List files in berita and banner folders to see where they are
echo "\n=== 4. Listing files in public_html/uploads/berita ===\n";
list_files($publicHtmlDir . '/uploads/berita');
echo "\n=== 5. Listing files in public_html/uploads/banner ===\n";
list_files($publicHtmlDir . '/uploads/banner');

echo "\n=== 6. Listing files in laravel/uploads/berita ===\n";
list_files($laravelDir . '/uploads/berita');
echo "\n=== 7. Listing files in laravel/uploads/banner ===\n";
list_files($laravelDir . '/uploads/banner');

function check_path($path) {
    if (!file_exists($path) && !is_link($path)) {
        echo "$path: ❌ Does not exist\n";
        return;
    }
    
    echo "$path:\n";
    echo "  - Type: " . (is_link($path) ? "LINK (Symlink)" : (is_dir($path) ? "Directory" : "File")) . "\n";
    if (is_link($path)) {
        echo "  - Target: " . readlink($path) . "\n";
    }
    echo "  - Readable: " . (is_readable($path) ? "YES" : "NO") . "\n";
    echo "  - Writable: " . (is_writable($path) ? "YES" : "NO") . "\n";
    echo "  - Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
}

function list_files($dir) {
    if (!is_dir($dir)) {
        echo "Directory $dir does not exist or is not a directory.\n";
        return;
    }
    $files = scandir($dir);
    $count = 0;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $count++;
        if ($count <= 10) {
            $filePath = $dir . '/' . $file;
            echo "  - $file (" . filesize($filePath) . " bytes)\n";
        }
    }
    if ($count > 10) {
        echo "  - ... and " . ($count - 10) . " more files\n";
    }
    if ($count === 0) {
        echo "  - (empty)\n";
    }
}

echo "\n</pre>";
