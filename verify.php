<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre>\n";

// 1. Check current index.php content
echo "=== Current index.php content ===\n";
echo htmlspecialchars(file_get_contents(__DIR__ . '/index.php')) . "\n";

// 2. Check if OPcache is enabled
echo "\n=== OPcache Status ===\n";
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    echo "OPcache enabled: " . ($status['opcache_enabled'] ? 'YES' : 'NO') . "\n";
    if ($status['opcache_enabled']) {
        echo "Cached scripts: " . $status['opcache_statistics']['num_cached_scripts'] . "\n";
        // Reset OPcache
        if (opcache_reset()) {
            echo "✅ OPcache RESET done!\n";
        }
    }
} else {
    echo "OPcache not available\n";
}

// 3. Check if index.php has the correct paths
echo "\n=== Path Check ===\n";
$indexContent = file_get_contents(__DIR__ . '/index.php');
if (strpos($indexContent, '../laravel/vendor') !== false) {
    echo "✅ index.php has ../laravel/ paths (correct)\n";
} else {
    echo "❌ index.php does NOT have ../laravel/ paths!\n";
}

// 4. Check the latest laravel.log (AFTER the new index.php)
echo "\n=== Fresh Laravel Log (last 30 lines) ===\n";
$logFile = __DIR__ . '/../laravel/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -30);
    echo htmlspecialchars(implode("", $lastLines));
} else {
    echo "No log file found\n";
}

// 5. Check .htaccess - maybe it's overriding to a different index
echo "\n\n=== .htaccess content ===\n";
$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    echo htmlspecialchars(file_get_contents($htaccess)) . "\n";
} else {
    echo "❌ NO .htaccess found!\n";
}

// 6. Try manual Laravel boot from THIS script with same paths as new index.php
echo "\n=== Manual Boot Test (same paths as index.php) ===\n";
try {
    require __DIR__ . '/../laravel/vendor/autoload.php';
    $app = require_once __DIR__ . '/../laravel/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::create('/api/banner', 'GET');
    $request->headers->set('Accept', 'application/json');
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() === 200) {
        echo "✅ Works! First 200 chars: " . htmlspecialchars(substr($response->getContent(), 0, 200)) . "\n";
    }
    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    echo "❌ " . $e->getMessage() . "\n";
}

echo "</pre>";
