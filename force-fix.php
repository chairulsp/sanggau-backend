<?php
/**
 * FORCE FIX - Clear Corrupted Cache & Regenerate
 * 
 * This fixes "Target class [db] does not exist" error
 * Upload ke root folder dan akses via browser
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Force Fix - Cache Clear</title>";
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

echo "<h1>🔧 Force Fix - Clear Corrupted Cache</h1>";
echo "<p>Fixing: Target class [db] does not exist</p>";
echo "<hr>";

$baseDir = __DIR__;
$steps = 0;
$errors = 0;

// ═══════════════════════════════════════════════════════════════════════════
// STEP 1: Delete ALL cached files
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 1: Deleting Cached Files</h2>";
echo "<pre>";

$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/routes.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php',
];

foreach ($cacheFiles as $file) {
    $path = $baseDir . '/' . $file;
    if (file_exists($path)) {
        if (@unlink($path)) {
            echo "✅ <span class='success'>Deleted: $file</span>\n";
            $steps++;
        } else {
            echo "❌ <span class='error'>Failed to delete: $file</span>\n";
            $errors++;
        }
    } else {
        echo "⚠️  <span class='warning'>Not found (already clean): $file</span>\n";
    }
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// STEP 2: Clear framework cache folders
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 2: Clearing Framework Cache</h2>";
echo "<pre>";

$cacheDirs = [
    'storage/framework/cache/data',
    'storage/framework/views',
    'storage/framework/sessions',
];

foreach ($cacheDirs as $dir) {
    $path = $baseDir . '/' . $dir;
    if (is_dir($path)) {
        $files = glob($path . '/*');
        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }
        echo "✅ <span class='success'>$dir: Deleted $deleted file(s)</span>\n";
        $steps++;
    } else {
        echo "⚠️  <span class='warning'>$dir: Directory not found</span>\n";
    }
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// STEP 3: Try Laravel artisan clear (if possible)
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 3: Laravel Artisan Clear</h2>";
echo "<pre>";

try {
    require $baseDir . '/vendor/autoload.php';
    $app = require_once $baseDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // Config clear
    try {
        $kernel->call('config:clear');
        echo "✅ <span class='success'>Config cache cleared</span>\n";
        $steps++;
    } catch (\Exception $e) {
        echo "⚠️  <span class='warning'>Config clear: " . $e->getMessage() . "</span>\n";
    }
    
    // Cache clear
    try {
        $kernel->call('cache:clear');
        echo "✅ <span class='success'>Application cache cleared</span>\n";
        $steps++;
    } catch (\Exception $e) {
        echo "⚠️  <span class='warning'>Cache clear: " . $e->getMessage() . "</span>\n";
    }
    
    // Route clear
    try {
        $kernel->call('route:clear');
        echo "✅ <span class='success'>Route cache cleared</span>\n";
        $steps++;
    } catch (\Exception $e) {
        echo "⚠️  <span class='warning'>Route clear: " . $e->getMessage() . "</span>\n";
    }
    
    // View clear (optional, might fail for API-only)
    try {
        $kernel->call('view:clear');
        echo "✅ <span class='success'>View cache cleared</span>\n";
        $steps++;
    } catch (\Exception $e) {
        echo "⚠️  <span class='warning'>View clear: " . $e->getMessage() . " (OK for API)</span>\n";
    }
    
} catch (\Exception $e) {
    echo "⚠️  <span class='warning'>Laravel artisan not available: " . $e->getMessage() . "</span>\n";
    echo "<span class='info'>Manual file deletion should be enough.</span>\n";
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// STEP 4: Verify .env readable
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 4: Verify .env File</h2>";
echo "<pre>";

$envPath = $baseDir . '/.env';
if (file_exists($envPath)) {
    if (is_readable($envPath)) {
        $size = filesize($envPath);
        echo "✅ <span class='success'>.env file readable ($size bytes)</span>\n";
        $steps++;
        
        // Check critical vars
        $content = file_get_contents($envPath);
        $criticalVars = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
        $missing = [];
        
        foreach ($criticalVars as $var) {
            if (strpos($content, $var . '=') === false) {
                $missing[] = $var;
            }
        }
        
        if (empty($missing)) {
            echo "✅ <span class='success'>All critical DB vars present</span>\n";
        } else {
            echo "⚠️  <span class='warning'>Missing vars: " . implode(', ', $missing) . "</span>\n";
        }
    } else {
        echo "❌ <span class='error'>.env file NOT READABLE! Check permissions.</span>\n";
        $errors++;
    }
} else {
    echo "❌ <span class='error'>.env file NOT FOUND!</span>\n";
    $errors++;
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// STEP 5: Test Bootstrap (will regenerate config)
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='step'>";
echo "<h2>STEP 5: Test Laravel Bootstrap</h2>";
echo "<pre>";

try {
    // Force reload
    $app = require $baseDir . '/bootstrap/app.php';
    echo "✅ <span class='success'>Laravel bootstrap successful</span>\n";
    $steps++;
    
    // Try to get db instance (will regenerate config)
    try {
        $db = $app->make('db');
        echo "✅ <span class='success'>DB service provider loaded</span>\n";
        $steps++;
        
        // Try connection
        try {
            $pdo = $db->connection()->getPdo();
            $dbName = $db->connection()->getDatabaseName();
            echo "✅ <span class='success'>Database connected: $dbName</span>\n";
            $steps++;
        } catch (\Exception $e) {
            echo "❌ <span class='error'>Database connection failed: " . $e->getMessage() . "</span>\n";
            $errors++;
        }
    } catch (\Exception $e) {
        echo "❌ <span class='error'>DB service provider failed: " . $e->getMessage() . "</span>\n";
        $errors++;
    }
    
} catch (\Exception $e) {
    echo "❌ <span class='error'>Bootstrap failed: " . $e->getMessage() . "</span>\n";
    $errors++;
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════════════════════════════════════
echo "<hr>";
echo "<div class='step'>";
echo "<h2>📊 SUMMARY</h2>";
echo "<pre>";
echo "Steps completed: <span class='success'>$steps</span>\n";
echo "Errors: <span class='" . ($errors > 0 ? 'error' : 'success') . "'>$errors</span>\n";
echo "</pre>";

if ($errors === 0) {
    echo "<div style='background: #4ec9b0; color: #1e1e1e; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #1e1e1e; margin: 0;'>🎉 FIX COMPLETE!</h2>";
    echo "<p style='margin: 10px 0 0 0;'>All cache cleared and Laravel can bootstrap successfully.</p>";
    echo "</div>";
    
    echo "<h2>✅ Next Steps:</h2>";
    echo "<pre class='success'>";
    echo "1. DELETE this file (force-fix.php)\n";
    echo "2. Test API endpoint: https://api.diskominfo.sanggau.go.id/api/banner\n";
    echo "3. Test frontend: https://diskominfo.sanggau.go.id\n";
    echo "4. Try login\n";
    echo "\nExpected result: Everything should work now! 🚀\n";
    echo "</pre>";
} else {
    echo "<div style='background: #f48771; color: #1e1e1e; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #1e1e1e; margin: 0;'>⚠️ ISSUES FOUND</h2>";
    echo "<p style='margin: 10px 0 0 0;'>Some steps failed. Check error messages above.</p>";
    echo "</div>";
    
    echo "<h2>🔧 Troubleshooting:</h2>";
    echo "<pre class='warning'>";
    echo "Common issues:\n\n";
    echo "1. .env file not readable\n";
    echo "   → Check file exists and permissions are 644\n\n";
    echo "2. DB credentials wrong\n";
    echo "   → Verify credentials in cPanel MySQL Databases\n\n";
    echo "3. Storage folders not writable\n";
    echo "   → Set permissions: chmod 755 storage/framework/*\n\n";
    echo "4. Composer dependencies missing\n";
    echo "   → Run: composer install\n";
    echo "</pre>";
}

echo "</div>";

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<h2 style='color: #1e1e1e; margin-top: 0;'>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY!</strong></p>";
echo "<p>File: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
