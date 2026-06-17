<?php
/**
 * Deep Debug - HAPUS SETELAH SELESAI!
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Deep Debug</h2><pre>\n";

$laravelPath = __DIR__ . '/../laravel';

// 1. Show index.php content
echo "=== index.php content ===\n";
$indexFile = __DIR__ . '/index.php';
if (file_exists($indexFile)) {
    echo htmlspecialchars(file_get_contents($indexFile)) . "\n";
} else {
    echo "❌ index.php NOT FOUND!\n";
    echo "Files in public_html:\n";
    foreach (scandir(__DIR__) as $f) {
        if ($f === '.' || $f === '..') continue;
        echo "  " . (is_dir(__DIR__.'/'.$f) ? '[DIR] ' : '      ') . $f . "\n";
    }
}

// 2. Show latest error log after cache clear
echo "\n=== Laravel Log (last 80 lines) ===\n";
$logFile = $laravelPath . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -80);
    echo htmlspecialchars(implode("", $lastLines));
} else {
    echo "No log file\n";
}

// 3. Check bootstrap/cache is empty now
echo "\n\n=== bootstrap/cache contents ===\n";
$cacheDir = $laravelPath . '/bootstrap/cache';
$cacheFiles = glob($cacheDir . '/*');
if (empty($cacheFiles)) {
    echo "Empty (good!)\n";
} else {
    foreach ($cacheFiles as $f) {
        echo "  " . basename($f) . " (" . filesize($f) . " bytes)\n";
    }
}

// 4. Check composer autoload
echo "\n=== Composer autoload check ===\n";
$autoload = $laravelPath . '/vendor/autoload.php';
if (file_exists($autoload)) {
    echo "✅ vendor/autoload.php exists\n";
    
    // Check if critical packages exist
    $criticalPaths = [
        '/vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
        '/vendor/laravel/framework/src/Illuminate/Database/DatabaseServiceProvider.php',
        '/vendor/laravel/sanctum/src/SanctumServiceProvider.php',
    ];
    foreach ($criticalPaths as $path) {
        $exists = file_exists($laravelPath . $path);
        echo ($exists ? "✅" : "❌") . " " . $path . "\n";
    }
} else {
    echo "❌ vendor/autoload.php NOT FOUND - need composer install!\n";
}

// 5. Try to boot Laravel properly and capture the REAL error
echo "\n=== Laravel Boot with Error Capture ===\n";
try {
    require $laravelPath . '/vendor/autoload.php';
    $app = require_once $laravelPath . '/bootstrap/app.php';
    
    echo "✅ App created\n";
    
    // Boot the kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel created\n";
    
    // Handle a request
    $request = Illuminate\Http\Request::create('/api/banner', 'GET');
    $request->headers->set('Accept', 'application/json');
    
    echo "Handling request...\n";
    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    
    $content = $response->getContent();
    if ($response->getStatusCode() === 200) {
        echo "✅ SUCCESS! Response (first 300 chars):\n";
        echo htmlspecialchars(substr($content, 0, 300)) . "\n";
    } else {
        echo "❌ Error response:\n";
        // Try to decode JSON error
        $json = json_decode($content, true);
        if ($json) {
            echo htmlspecialchars(json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "\n";
        } else {
            echo htmlspecialchars(substr($content, 0, 500)) . "\n";
        }
    }
    
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n</pre>";
