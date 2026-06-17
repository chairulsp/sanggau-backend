<?php
/**
 * FINAL FIX - Regenerate Service Container
 * 
 * Fixes: Target class [db] does not exist
 * By regenerating Laravel service container bindings
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Final Fix - Service Container</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; font-size: 14px; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h1 { color: #569cd6; }
    h2 { color: #4ec9b0; margin-top: 2rem; }
    pre { background: #252526; padding: 15px; border-left: 3px solid #007acc; line-height: 1.6; }
    .step { background: #252526; padding: 20px; margin: 20px 0; border-radius: 5px; }
</style></head><body>";

echo "<h1>🔧 Final Fix - Regenerate Service Container</h1>";
echo "<p>Fixing: App has 'db' binding: No</p>";
echo "<hr>";

$baseDir = __DIR__;
$fixed = false;

// ═══════════════════════════════════════════════════════════════════════════
// STEP 1: Delete services cache
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 1: Delete Cached Services</h2>";
echo "<pre>";

$servicesCache = $baseDir . '/bootstrap/cache/services.php';
if (file_exists($servicesCache)) {
    if (@unlink($servicesCache)) {
        echo "✅ <span class='success'>Deleted: bootstrap/cache/services.php</span>\n";
    } else {
        echo "❌ <span class='error'>Failed to delete services.php</span>\n";
    }
} else {
    echo "⚠️  <span class='warning'>services.php not found (already deleted)</span>\n";
}

// Also delete packages
$packagesCache = $baseDir . '/bootstrap/cache/packages.php';
if (file_exists($packagesCache)) {
    if (@unlink($packagesCache)) {
        echo "✅ <span class='success'>Deleted: bootstrap/cache/packages.php</span>\n";
    } else {
        echo "❌ <span class='error'>Failed to delete packages.php</span>\n";
    }
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// STEP 2: Force reload Laravel app
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 2: Force Reload Laravel</h2>";
echo "<pre>";

try {
    // Clear opcache if available
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✅ <span class='success'>OPcache reset</span>\n";
    }
    
    // Require fresh autoload
    require $baseDir . '/vendor/autoload.php';
    echo "✅ <span class='success'>Autoload loaded</span>\n";
    
    // Bootstrap app (will regenerate services cache)
    $app = require $baseDir . '/bootstrap/app.php';
    echo "✅ <span class='success'>App bootstrapped</span>\n";
    
    // Check if db binding exists NOW
    $hasDb = $app->bound('db');
    echo "\nChecking 'db' binding: ";
    
    if ($hasDb) {
        echo "<span class='success'>✅ YES! Binding exists now!</span>\n\n";
        
        // Try to actually use it
        try {
            $db = $app->make('db');
            echo "✅ <span class='success'>Successfully created DB instance</span>\n";
            
            $connection = $db->connection();
            echo "✅ <span class='success'>Got database connection</span>\n";
            
            $pdo = $connection->getPdo();
            $dbName = $connection->getDatabaseName();
            echo "✅ <span class='success'>Connected to: $dbName</span>\n";
            
            // Test query
            $result = $db->select('SELECT COUNT(*) as total FROM banners');
            $count = $result[0]->total ?? 0;
            echo "✅ <span class='success'>Test query successful (banners: $count)</span>\n";
            
            $fixed = true;
            
        } catch (\Exception $e) {
            echo "❌ <span class='error'>DB instance failed: " . $e->getMessage() . "</span>\n";
        }
        
    } else {
        echo "<span class='error'>❌ NO! Still not bound</span>\n\n";
        echo "<span class='warning'>This shouldn't happen. Checking bootstrap process...</span>\n";
        
        // Debug: Check if service provider actually ran
        $providers = $app->getLoadedProviders();
        $dbProviderLoaded = false;
        foreach ($providers as $provider => $loaded) {
            if (strpos($provider, 'DatabaseServiceProvider') !== false) {
                echo "Found provider: <span class='info'>$provider</span> = " . ($loaded ? 'loaded' : 'not loaded') . "\n";
                $dbProviderLoaded = true;
            }
        }
        
        if (!$dbProviderLoaded) {
            echo "<span class='error'>DatabaseServiceProvider was not loaded!</span>\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ <span class='error'>Bootstrap failed: " . $e->getMessage() . "</span>\n";
    echo "\n<span class='warning'>Stack trace:</span>\n";
    echo htmlspecialchars($e->getTraceAsString());
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// STEP 3: Run artisan optimize
// ═══════════════════════════════════════════════════════════════════════════
if ($fixed) {
    echo "<div class='step'>";
    echo "<h2>STEP 3: Optimize Application</h2>";
    echo "<pre>";
    
    try {
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        // Clear all
        $kernel->call('optimize:clear');
        echo "✅ <span class='success'>optimize:clear executed</span>\n";
        
        // Config cache (safe to cache now)
        try {
            $kernel->call('config:cache');
            echo "✅ <span class='success'>Config cached</span>\n";
        } catch (\Exception $e) {
            echo "⚠️  <span class='warning'>Config cache skipped: " . $e->getMessage() . "</span>\n";
        }
        
    } catch (\Exception $e) {
        echo "⚠️  <span class='warning'>Optimize skipped: " . $e->getMessage() . "</span>\n";
    }
    
    echo "</pre>";
    echo "</div>";
}

// ═══════════════════════════════════════════════════════════════════════════
// STEP 4: Test API Endpoints
// ═══════════════════════════════════════════════════════════════════════════
if ($fixed) {
    echo "<div class='step'>";
    echo "<h2>STEP 4: Test API Endpoints</h2>";
    echo "<pre>";
    
    $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
    $endpoints = ['/api/banner', '/api/berita', '/api/settings'];
    
    foreach ($endpoints as $path) {
        $url = $baseUrl . $path;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            $data = json_decode($response, true);
            $count = is_array($data) ? count($data) : '?';
            echo "✅ <span class='success'>$path</span> → HTTP 200 ($count items)\n";
        } else {
            echo "⚠️  <span class='warning'>$path</span> → HTTP $httpCode\n";
        }
    }
    
    echo "</pre>";
    echo "</div>";
}

// ═══════════════════════════════════════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════════════════════════════════════
echo "<hr>";

if ($fixed) {
    echo "<div style='background: #4ec9b0; color: #1e1e1e; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h1 style='color: #1e1e1e; margin: 0;'>🎉 FIXED!</h1>";
    echo "<p style='margin: 10px 0 0 0; font-size: 16px;'><strong>Database service is now working!</strong></p>";
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h2>✅ What Was Fixed:</h2>";
    echo "<pre class='success'>";
    echo "✅ Service container regenerated\n";
    echo "✅ DatabaseServiceProvider properly loaded\n";
    echo "✅ 'db' binding now exists in container\n";
    echo "✅ Database connection working\n";
    echo "✅ API endpoints should work now\n";
    echo "</pre>";
    
    echo "<h2>🚀 Next Steps:</h2>";
    echo "<pre class='info'>";
    echo "1. DELETE this file (final-fix.php)\n";
    echo "2. DELETE debug-db.php\n";
    echo "3. DELETE force-fix.php\n";
    echo "4. DELETE full-diagnostic.php\n";
    echo "5. Test frontend: https://diskominfo.sanggau.go.id\n";
    echo "6. Try login to admin panel\n";
    echo "7. Test create/edit berita\n";
    echo "\n<strong>Everything should work now! 🎉</strong>\n";
    echo "</pre>";
    echo "</div>";
    
} else {
    echo "<div style='background: #f48771; color: #1e1e1e; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #1e1e1e; margin: 0;'>⚠️ Not Fixed Yet</h2>";
    echo "<p style='margin: 10px 0 0 0;'>Service binding still not working. Manual intervention required.</p>";
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h2>🔧 Manual Fix Required:</h2>";
    echo "<pre class='warning'>";
    echo "Via SSH or cPanel Terminal:\n\n";
    echo "cd /home/diskominfo/public_html\n";
    echo "composer dump-autoload\n";
    echo "php artisan optimize:clear\n";
    echo "php artisan config:cache\n";
    echo "\nOr contact hosting support for assistance.\n";
    echo "</pre>";
    echo "</div>";
}

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<h2 style='color: #1e1e1e; margin-top: 0;'>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE AND ALL DEBUG FILES NOW!</strong></p>";
echo "<p>Files to delete:</p>";
echo "<ul style='margin: 5px 0;'>";
echo "<li>final-fix.php (this file)</li>";
echo "<li>debug-db.php</li>";
echo "<li>force-fix.php</li>";
echo "<li>full-diagnostic.php</li>";
echo "<li>server-troubleshooting.php</li>";
echo "<li>clear-cache.php</li>";
echo "<li>test-api.php</li>";
echo "<li>check-routes.php</li>";
echo "<li>fix-storage-structure.php</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
?>
