<?php
/**
 * Fix Laravel Server Script - HAPUS SETELAH SELESAI!
 * Upload ke: /home/diskominfo/public_html/fix_server.php
 * Access: https://api.diskominfo.sanggau.go.id/fix_server.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Laravel Fix Script</h2><pre>\n";

$laravelPath = __DIR__ . '/../laravel';

// ==========================================
// STEP 1: Delete ALL bootstrap cache files
// ==========================================
echo "=== Step 1: Clear Bootstrap Cache ===\n";
$cacheDir = $laravelPath . '/bootstrap/cache';
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '/*.php');
    foreach ($files as $file) {
        $basename = basename($file);
        if (unlink($file)) {
            echo "✅ Deleted: bootstrap/cache/{$basename}\n";
        } else {
            echo "❌ Failed to delete: bootstrap/cache/{$basename}\n";
        }
    }
    if (empty($files)) {
        echo "ℹ️  No cached .php files found in bootstrap/cache/\n";
    }
} else {
    echo "❌ bootstrap/cache directory not found!\n";
}

// ==========================================
// STEP 2: Check config/app.php exists and has providers
// ==========================================
echo "\n=== Step 2: Check config/app.php ===\n";
$configApp = $laravelPath . '/config/app.php';
if (file_exists($configApp)) {
    $config = include $configApp;
    if (isset($config['providers']) && is_array($config['providers'])) {
        echo "✅ config/app.php has " . count($config['providers']) . " providers\n";
        
        // Check for critical providers
        $critical = [
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Translation\TranslationServiceProvider',
            'Illuminate\Validation\ValidationServiceProvider',
            'Illuminate\Auth\AuthServiceProvider',
            'Illuminate\Routing\RoutingServiceProvider',
        ];
        foreach ($critical as $provider) {
            $found = in_array($provider, $config['providers']);
            echo ($found ? "✅" : "❌") . " {$provider}: " . ($found ? "OK" : "MISSING!") . "\n";
        }
    } else {
        echo "❌ config/app.php is missing 'providers' array!\n";
    }
} else {
    echo "❌ config/app.php NOT FOUND! This is the problem!\n";
    echo "   Checking config directory...\n";
    if (is_dir($laravelPath . '/config')) {
        $configFiles = scandir($laravelPath . '/config');
        echo "   Config files: " . implode(', ', array_filter($configFiles, fn($f) => $f !== '.' && $f !== '..')) . "\n";
    } else {
        echo "   ❌ config/ directory doesn't exist!\n";
    }
}

// ==========================================
// STEP 3: Check .htaccess
// ==========================================
echo "\n=== Step 3: Check .htaccess ===\n";
$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    echo "✅ .htaccess exists\n";
    echo "Content:\n";
    echo file_get_contents($htaccess) . "\n";
} else {
    echo "❌ .htaccess NOT FOUND! Creating one...\n";
    $htContent = '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
';
    if (file_put_contents($htaccess, $htContent)) {
        echo "✅ .htaccess created!\n";
    } else {
        echo "❌ Failed to create .htaccess\n";
    }
}

// ==========================================
// STEP 4: Ensure storage directories exist with proper permissions
// ==========================================
echo "\n=== Step 4: Fix Storage Permissions ===\n";
$storageDirs = [
    '/storage/app/public',
    '/storage/framework/cache/data',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/storage/logs',
];
foreach ($storageDirs as $dir) {
    $fullPath = $laravelPath . $dir;
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0775, true)) {
            echo "✅ Created: {$dir}\n";
        } else {
            echo "❌ Failed to create: {$dir}\n";
        }
    } else {
        echo "✅ Exists: {$dir}\n";
    }
    // Try to set permissions
    @chmod($fullPath, 0775);
}

// Set writable on storage root
@chmod($laravelPath . '/storage', 0775);
@chmod($laravelPath . '/bootstrap/cache', 0775);

// ==========================================
// STEP 5: Check index.php
// ==========================================
echo "\n=== Step 5: Check index.php ===\n";
$indexFile = __DIR__ . '/index.php';
if (file_exists($indexFile)) {
    $indexContent = file_get_contents($indexFile);
    echo "✅ index.php exists\n";
    
    // Check if it points to correct Laravel paths
    if (strpos($indexContent, '../laravel') !== false) {
        echo "✅ index.php references ../laravel (correct)\n";
    } else {
        echo "⚠️  index.php content:\n";
        echo $indexContent . "\n";
    }
} else {
    echo "❌ index.php NOT FOUND!\n";
}

// ==========================================
// STEP 6: Try booting Laravel again
// ==========================================
echo "\n=== Step 6: Test Laravel After Fix ===\n";
try {
    // Need to reset the app since we might have loaded it in debug_server.php
    // Clear opcache if available
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✅ OPcache cleared\n";
    }
    
    require $laravelPath . '/vendor/autoload.php';
    
    // Force fresh app instance
    $app = require $laravelPath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Create a fake request to boot all providers
    $request = Illuminate\Http\Request::create('/api/banner', 'GET');
    $response = $kernel->handle($request);
    
    echo "✅ Response status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 200) {
        $content = $response->getContent();
        $data = json_decode($content, true);
        if (is_array($data)) {
            echo "✅ API /api/banner returned " . count($data) . " items\n";
        } else {
            echo "Response (first 500 chars): " . substr($content, 0, 500) . "\n";
        }
    } else {
        echo "Response (first 500 chars): " . substr($response->getContent(), 0, 500) . "\n";
    }
    
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (Error $e) {
    echo "❌ Fatal: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n</pre>";
echo "<p>⚠️ <strong>HAPUS FILE INI SETELAH SELESAI!</strong></p>";
