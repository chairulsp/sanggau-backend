<?php
/**
 * SANGGAU BACKEND - CACHE CLEANER
 * 
 * Upload file ini ke root folder di cPanel untuk clear cache
 * Akses: https://api.diskominfo.sanggau.go.id/clear-cache.php
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI UNTUK KEAMANAN!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Sanggau Backend - Clear Cache</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .section { margin: 20px 0; padding: 15px; background: #252526; border-left: 3px solid #007acc; }
    h2 { color: #569cd6; }
    pre { background: #1e1e1e; padding: 10px; overflow-x: auto; }
    .btn { display: inline-block; padding: 10px 20px; background: #007acc; color: white; text-decoration: none; border-radius: 3px; margin: 5px; }
    .btn:hover { background: #005a9e; }
</style></head><body>";

echo "<h1>🧹 Sanggau Backend - Cache Cleaner</h1>";
echo "<p>Waktu: " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// Check if action is requested
$action = $_GET['action'] ?? '';

if (empty($action)) {
    // Show menu
    echo "<div class='section'>";
    echo "<h2>Pilih Aksi:</h2>";
    echo "<p>";
    echo "<a href='?action=clear_all' class='btn'>🧹 Clear All Cache</a>";
    echo "<a href='?action=clear_config' class='btn'>⚙️ Clear Config Cache</a>";
    echo "<a href='?action=clear_route' class='btn'>🛣️ Clear Route Cache</a>";
    echo "<a href='?action=clear_view' class='btn'>👁️ Clear View Cache</a>";
    echo "</p>";
    echo "<p><a href='?action=rebuild' class='btn' style='background: #4ec9b0;'>🔄 Rebuild Cache (Recommended after clear)</a></p>";
    echo "</div>";
    
    echo "<div class='section' style='border-left-color: #f48771;'>";
    echo "<h2 style='color: #f48771;'>⚠️ SECURITY WARNING</h2>";
    echo "<p><strong>DELETE THIS FILE AFTER USE!</strong></p>";
    echo "<p>File: <code>" . __FILE__ . "</code></p>";
    echo "</div>";
    
} else {
    // Execute action
    echo "<div class='section'>";
    echo "<h2>Executing...</h2>";
    echo "<pre>";
    
    try {
        require __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        switch ($action) {
            case 'clear_all':
                echo "Clearing all caches...\n\n";
                
                $kernel->call('config:clear');
                echo "✅ <span class='success'>Config cache cleared</span>\n";
                
                $kernel->call('cache:clear');
                echo "✅ <span class='success'>Application cache cleared</span>\n";
                
                $kernel->call('route:clear');
                echo "✅ <span class='success'>Route cache cleared</span>\n";
                
                // Skip view:clear for API-only apps (no Blade views)
                try {
                    $kernel->call('view:clear');
                    echo "✅ <span class='success'>View cache cleared</span>\n";
                } catch (\Exception $e) {
                    echo "⚠️  <span class='warning'>View cache skipped (API only, no views needed)</span>\n";
                }
                
                echo "\n<span class='success'>All critical caches cleared successfully!</span>\n";
                echo "\n<span class='warning'>Recommended: </span>";
                echo "<a href='?action=rebuild'>Rebuild cache now</a>\n";
                break;
                
            case 'clear_config':
                $kernel->call('config:clear');
                echo "✅ <span class='success'>Config cache cleared</span>\n";
                echo "\n<span class='warning'>Note: Config will be re-cached on next request</span>\n";
                break;
                
            case 'clear_route':
                $kernel->call('route:clear');
                echo "✅ <span class='success'>Route cache cleared</span>\n";
                break;
                
            case 'clear_view':
                try {
                    $kernel->call('view:clear');
                    echo "✅ <span class='success'>View cache cleared</span>\n";
                } catch (\Exception $e) {
                    echo "⚠️  <span class='warning'>View cache skipped (API only, no views needed)</span>\n";
                }
                break;
                
            case 'rebuild':
                echo "Rebuilding caches...\n\n";
                
                try {
                    $kernel->call('config:cache');
                    echo "✅ <span class='success'>Config cached</span>\n";
                } catch (\Exception $e) {
                    echo "❌ <span class='error'>Config cache failed: " . $e->getMessage() . "</span>\n";
                }
                
                try {
                    $kernel->call('route:cache');
                    echo "✅ <span class='success'>Routes cached</span>\n";
                } catch (\Exception $e) {
                    echo "⚠️  <span class='warning'>Route cache failed (this is OK for now)</span>\n";
                    echo "<span class='info'>Error: " . $e->getMessage() . "</span>\n\n";
                    echo "<span class='warning'>Common causes:</span>\n";
                    echo "- Duplicate route names in routes/web.php or routes/api.php\n";
                    echo "- Closure-based routes (not cacheable)\n\n";
                    echo "<span class='info'>Application will work without route cache, just slightly slower.</span>\n";
                }
                
                echo "\n<span class='success'>Cache rebuild completed!</span>\n";
                break;
                
            default:
                echo "❌ <span class='error'>Unknown action</span>\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ <span class='error'>Error: " . $e->getMessage() . "</span>\n";
        echo "\n<span class='warning'>Possible solutions:</span>\n";
        echo "1. Check if composer dependencies are installed\n";
        echo "2. Verify .env file exists and is readable\n";
        echo "3. Check folder permissions (storage/bootstrap/cache)\n";
    }
    
    echo "</pre>";
    echo "<p><a href='?' class='btn'>← Back to Menu</a></p>";
    echo "</div>";
}

echo "<div class='section'>";
echo "<h2>Manual Cache Cleanup</h2>";
echo "<p>Jika script ini tidak berfungsi, Anda bisa hapus cache manual via File Manager:</p>";
echo "<pre>";
echo "bootstrap/cache/config.php\n";
echo "bootstrap/cache/routes-v7.php\n";
echo "bootstrap/cache/services.php\n";
echo "storage/framework/cache/*\n";
echo "storage/framework/views/*\n";
echo "</pre>";
echo "</div>";

echo "</body></html>";
?>
