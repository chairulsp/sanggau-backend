<?php
/**
 * Debug Server Script - HAPUS SETELAH SELESAI!
 * Upload ke: /home/diskomi5/public_html/debug_server.php
 * Access: https://api.diskominfo.sanggau.go.id/debug_server.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Server Debug Info</h2><pre>\n";

// 1. PHP Version
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n\n";

// 2. Check Laravel directory
$laravelPath = __DIR__ . '/../laravel';
echo "=== Laravel Directory ===\n";
echo "Laravel path: {$laravelPath}\n";
echo "Laravel exists: " . (is_dir($laravelPath) ? 'YES' : 'NO') . "\n";

if (is_dir($laravelPath)) {
    echo "vendor exists: " . (is_dir($laravelPath . '/vendor') ? 'YES' : 'NO') . "\n";
    echo "bootstrap exists: " . (is_dir($laravelPath . '/bootstrap') ? 'YES' : 'NO') . "\n";
    echo ".env exists: " . (file_exists($laravelPath . '/.env') ? 'YES' : 'NO') . "\n";
    echo "storage exists: " . (is_dir($laravelPath . '/storage') ? 'YES' : 'NO') . "\n";
    echo "storage/logs exists: " . (is_dir($laravelPath . '/storage/logs') ? 'YES' : 'NO') . "\n";
    echo "storage/framework exists: " . (is_dir($laravelPath . '/storage/framework') ? 'YES' : 'NO') . "\n";
    echo "storage/framework/sessions: " . (is_dir($laravelPath . '/storage/framework/sessions') ? 'YES' : 'NO') . "\n";
    echo "storage/framework/views: " . (is_dir($laravelPath . '/storage/framework/views') ? 'YES' : 'NO') . "\n";
    echo "storage/framework/cache: " . (is_dir($laravelPath . '/storage/framework/cache') ? 'YES' : 'NO') . "\n";
    echo "storage/framework/cache/data: " . (is_dir($laravelPath . '/storage/framework/cache/data') ? 'YES' : 'NO') . "\n";
    
    // Check .env content (hide sensitive info)
    if (file_exists($laravelPath . '/.env')) {
        echo "\n=== .env Key Settings (sanitized) ===\n";
        $env = file_get_contents($laravelPath . '/.env');
        $lines = explode("\n", $env);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] === '#') continue;
            
            // Show key names but hide sensitive values
            $parts = explode('=', $line, 2);
            $key = $parts[0];
            $val = $parts[1] ?? '';
            
            // Hide passwords and keys
            $sensitiveKeys = ['PASSWORD', 'KEY', 'SECRET', 'TOKEN'];
            $isSensitive = false;
            foreach ($sensitiveKeys as $sk) {
                if (stripos($key, $sk) !== false) {
                    $isSensitive = true;
                    break;
                }
            }
            
            if ($isSensitive) {
                echo "{$key}=" . (empty(trim($val, '"\'')) ? '(empty)' : '***hidden***') . "\n";
            } else {
                echo "{$key}={$val}\n";
            }
        }
    }
    
    // Check latest Laravel log
    $logFile = $laravelPath . '/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        echo "\n=== Latest Laravel Log (last 50 lines) ===\n";
        $lines = file($logFile);
        $lastLines = array_slice($lines, -50);
        echo implode("", $lastLines);
    } else {
        echo "\n⚠️ No laravel.log found at: {$logFile}\n";
    }
} else {
    echo "\n❌ Laravel directory NOT found at: {$laravelPath}\n";
    echo "Current directory: " . __DIR__ . "\n";
    echo "\nFiles in parent directory:\n";
    if (is_dir(__DIR__ . '/..')) {
        $files = scandir(__DIR__ . '/..');
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            echo "  " . (is_dir(__DIR__ . '/../' . $f) ? '[DIR] ' : '      ') . $f . "\n";
        }
    }
}

// 3. Check storage symlink
echo "\n=== Storage Symlink ===\n";
$storageLink = __DIR__ . '/storage';
echo "storage link exists: " . (file_exists($storageLink) ? 'YES' : 'NO') . "\n";
echo "storage is symlink: " . (is_link($storageLink) ? 'YES' : 'NO') . "\n";
if (is_link($storageLink)) {
    echo "storage target: " . readlink($storageLink) . "\n";
}

// 4. Check uploads directory
echo "\n=== Uploads Directory ===\n";
echo "uploads exists: " . (is_dir(__DIR__ . '/uploads') ? 'YES' : 'NO') . "\n";
echo "uploads/banner exists: " . (is_dir(__DIR__ . '/uploads/banner') ? 'YES' : 'NO') . "\n";
if (is_dir(__DIR__ . '/uploads/banner')) {
    $bannerFiles = scandir(__DIR__ . '/uploads/banner');
    $bannerFiles = array_filter($bannerFiles, fn($f) => $f !== '.' && $f !== '..');
    echo "Banner files count: " . count($bannerFiles) . "\n";
    foreach (array_slice($bannerFiles, 0, 5) as $f) {
        echo "  - {$f}\n";
    }
}

// 5. Try to boot Laravel and get the actual error
echo "\n=== Laravel Boot Test ===\n";
try {
    require $laravelPath . '/vendor/autoload.php';
    $app = require_once $laravelPath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Laravel booted successfully!\n";
    
    // Test DB connection
    try {
        $pdo = $app->make('db')->connection()->getPdo();
        echo "✅ Database connected: " . $pdo->getAttribute(PDO::ATTR_SERVER_INFO) . "\n";
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Laravel boot error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (Error $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n</pre>";
echo "<p>⚠️ <strong>HAPUS FILE INI SETELAH SELESAI DEBUG!</strong></p>";
