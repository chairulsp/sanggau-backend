<?php
/**
 * FULL DIAGNOSTIC - Sanggau Backend
 * 
 * Comprehensive check untuk semua aspek sistem
 * Upload ke root folder dan akses via browser
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Full Diagnostic</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; font-size: 13px; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h1 { color: #569cd6; font-size: 24px; }
    h2 { color: #4ec9b0; margin-top: 2rem; font-size: 18px; }
    pre { background: #252526; padding: 15px; overflow-x: auto; border-left: 3px solid #007acc; font-size: 12px; line-height: 1.5; }
    .section { margin: 30px 0; padding: 20px; background: #252526; border-radius: 5px; }
    .critical { background: #f48771; color: #1e1e1e; padding: 15px; border-radius: 5px; margin: 10px 0; font-weight: bold; }
</style></head><body>";

echo "<h1>🔍 Full System Diagnostic</h1>";
echo "<p>Waktu: " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

$criticalIssues = [];
$warnings = [];
$passed = 0;
$failed = 0;

// ═══════════════════════════════════════════════════════════════════════════
// 1. BASIC PHP INFO
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>1️⃣ PHP Environment</h2>";
echo "<pre>";
echo "PHP Version: <span class='success'>" . phpversion() . "</span>\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script Filename: " . __FILE__ . "\n";
echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 2. CRITICAL FILES CHECK
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>2️⃣ Critical Files</h2>";
echo "<pre>";

$criticalFiles = [
    '.env' => 'Environment configuration',
    'vendor/autoload.php' => 'Composer dependencies',
    'bootstrap/app.php' => 'Laravel bootstrap',
    'app/Http/Kernel.php' => 'HTTP Kernel',
    'routes/api.php' => 'API routes',
    'routes/web.php' => 'Web routes',
    '.htaccess' => 'Apache rewrite rules',
];

foreach ($criticalFiles as $file => $desc) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "✅ <span class='success'>$file</span> ($desc)\n";
        $passed++;
    } else {
        echo "❌ <span class='error'>$file NOT FOUND!</span> ($desc)\n";
        $criticalIssues[] = "Missing critical file: $file";
        $failed++;
    }
}
echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 3. STORAGE FOLDERS
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>3️⃣ Storage Structure</h2>";
echo "<pre>";

$storageFolders = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($storageFolders as $folder) {
    $path = __DIR__ . '/' . $folder;
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -3);
        $writable = is_writable($path);
        if ($writable) {
            echo "✅ <span class='success'>$folder</span> (perm: $perms)\n";
            $passed++;
        } else {
            echo "⚠️  <span class='warning'>$folder</span> exists but NOT WRITABLE (perm: $perms)\n";
            $warnings[] = "Folder not writable: $folder";
            $failed++;
        }
    } else {
        echo "❌ <span class='error'>$folder NOT FOUND!</span>\n";
        $criticalIssues[] = "Missing storage folder: $folder";
        $failed++;
    }
}
echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 4. LARAVEL BOOTSTRAP TEST
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>4️⃣ Laravel Bootstrap</h2>";
echo "<pre>";

$laravelWorks = false;
$app = null;

try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ <span class='success'>Laravel bootstrap successful</span>\n";
    $laravelWorks = true;
    $passed++;
} catch (\Exception $e) {
    echo "❌ <span class='error'>Laravel bootstrap FAILED!</span>\n";
    echo "<span class='error'>Error: " . $e->getMessage() . "</span>\n";
    $criticalIssues[] = "Laravel cannot bootstrap: " . $e->getMessage();
    $failed++;
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 5. DATABASE CONNECTION
// ═══════════════════════════════════════════════════════════════════════════
if ($laravelWorks && $app) {
    echo "<div class='section'>";
    echo "<h2>5️⃣ Database Connection</h2>";
    echo "<pre>";
    
    try {
        $db = $app->make('db');
        $pdo = $db->connection()->getPdo();
        
        echo "✅ <span class='success'>Database connected!</span>\n";
        echo "Database: <span class='info'>" . $db->connection()->getDatabaseName() . "</span>\n";
        echo "Driver: " . $db->connection()->getDriverName() . "\n";
        
        // Test query
        $result = $db->select('SELECT VERSION() as version');
        if (!empty($result)) {
            echo "MySQL Version: <span class='success'>" . $result[0]->version . "</span>\n";
        }
        
        // Check tables
        $tables = $db->select('SHOW TABLES');
        echo "Tables count: <span class='success'>" . count($tables) . "</span>\n";
        $passed++;
        
    } catch (\Exception $e) {
        echo "❌ <span class='error'>Database connection FAILED!</span>\n";
        echo "<span class='error'>Error: " . $e->getMessage() . "</span>\n";
        $criticalIssues[] = "Database not connected: " . $e->getMessage();
        $failed++;
    }
    
    echo "</pre>";
    echo "</div>";
}

// ═══════════════════════════════════════════════════════════════════════════
// 6. TEST API ENDPOINTS
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>6️⃣ API Endpoints Test</h2>";
echo "<pre>";

$baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
$endpoints = [
    '/api/banner',
    '/api/berita',
    '/api/settings',
];

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
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        echo "❌ <span class='error'>$path</span> → CURL Error: $curlError\n";
        $failed++;
    } else if ($httpCode == 200) {
        $data = json_decode($response, true);
        $count = is_array($data) ? count($data) : '?';
        echo "✅ <span class='success'>$path</span> → HTTP $httpCode ($count items)\n";
        $passed++;
    } else if ($httpCode == 500) {
        echo "❌ <span class='error'>$path</span> → HTTP 500 (Server Error)\n";
        $criticalIssues[] = "API endpoint error 500: $path";
        $failed++;
    } else {
        echo "⚠️  <span class='warning'>$path</span> → HTTP $httpCode\n";
        $warnings[] = "API endpoint returned HTTP $httpCode: $path";
        $failed++;
    }
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 7. .ENV CONFIGURATION
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>7️⃣ Environment Configuration</h2>";
echo "<pre>";

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "✅ <span class='success'>.env file exists</span>\n\n";
    
    $envContent = file_get_contents($envFile);
    $lines = explode("\n", $envContent);
    
    $importantKeys = [
        'APP_ENV',
        'APP_DEBUG',
        'APP_URL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
    ];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        
        foreach ($importantKeys as $key) {
            if (strpos($line, $key . '=') === 0) {
                if (strpos($line, 'PASSWORD') !== false) {
                    $value = substr($line, strlen($key . '='));
                    if (empty(trim($value))) {
                        echo "<span class='warning'>$key=<EMPTY></span>\n";
                        $warnings[] = "DB_PASSWORD is empty";
                    } else {
                        echo "$key=<span class='warning'>***SET***</span>\n";
                    }
                } else {
                    echo "<span class='info'>$line</span>\n";
                }
                break;
            }
        }
    }
} else {
    echo "❌ <span class='error'>.env file NOT FOUND!</span>\n";
    $criticalIssues[] = "Missing .env file";
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 8. RECENT ERROR LOGS
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>8️⃣ Recent Error Logs</h2>";
echo "<pre>";

$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $size = filesize($logFile);
    echo "Log file size: " . number_format($size) . " bytes\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($logFile)) . "\n\n";
    
    // Read last 50 lines
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    
    echo "<span class='warning'>Last 50 lines (showing errors/exceptions only):</span>\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $foundError = false;
    foreach ($lastLines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        if (
            stripos($line, 'error') !== false ||
            stripos($line, 'exception') !== false ||
            stripos($line, 'fatal') !== false ||
            stripos($line, 'failed') !== false
        ) {
            echo "<span class='error'>" . htmlspecialchars(substr($line, 0, 500)) . "</span>\n";
            $foundError = true;
        }
    }
    
    if (!$foundError) {
        echo "<span class='success'>✅ No recent errors found in log!</span>\n";
    }
} else {
    echo "⚠️  <span class='warning'>No log file found (no errors logged yet)</span>\n";
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// 9. .HTACCESS CHECK
// ═══════════════════════════════════════════════════════════════════════════
echo "<div class='section'>";
echo "<h2>9️⃣ Apache Configuration</h2>";
echo "<pre>";

$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    echo "✅ <span class='success'>.htaccess exists</span>\n";
    $content = file_get_contents($htaccess);
    if (stripos($content, 'RewriteEngine On') !== false) {
        echo "✅ <span class='success'>RewriteEngine is enabled</span>\n";
        $passed++;
    } else {
        echo "⚠️  <span class='warning'>RewriteEngine directive not found</span>\n";
        $warnings[] = ".htaccess missing RewriteEngine On";
    }
} else {
    echo "❌ <span class='error'>.htaccess NOT FOUND!</span>\n";
    $criticalIssues[] = "Missing .htaccess file";
    $failed++;
}

// Check mod_rewrite
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "✅ <span class='success'>mod_rewrite is loaded</span>\n";
    } else {
        echo "⚠️  <span class='warning'>mod_rewrite status unknown or not loaded</span>\n";
        $warnings[] = "mod_rewrite might not be enabled";
    }
}

echo "</pre>";
echo "</div>";

// ═══════════════════════════════════════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════════════════════════════════════
echo "<hr>";
echo "<div class='section'>";
echo "<h2>📊 SUMMARY</h2>";
echo "<pre>";
echo "<span class='success'>✅ Passed: $passed</span>\n";
echo "<span class='error'>❌ Failed: $failed</span>\n";
echo "<span class='warning'>⚠️  Warnings: " . count($warnings) . "</span>\n";
echo "</pre>";

if (!empty($criticalIssues)) {
    echo "<div class='critical'>";
    echo "🚨 CRITICAL ISSUES FOUND (" . count($criticalIssues) . "):\n\n";
    foreach ($criticalIssues as $i => $issue) {
        echo ($i + 1) . ". $issue\n";
    }
    echo "</div>";
    
    echo "<h2>🔧 Next Steps:</h2>";
    echo "<pre class='warning'>";
    echo "1. Fix critical issues listed above\n";
    echo "2. Run fix-storage-structure.php if storage folders missing\n";
    echo "3. Check database credentials in .env\n";
    echo "4. Clear Laravel cache\n";
    echo "5. Run this diagnostic again\n";
    echo "</pre>";
} else if ($failed > 0) {
    echo "<h2>⚠️ Issues Found</h2>";
    echo "<p>There are some failed checks. Review the sections above marked with ❌</p>";
} else {
    echo "<div style='background: #4ec9b0; color: #1e1e1e; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #1e1e1e; margin: 0;'>🎉 ALL CHECKS PASSED!</h2>";
    echo "<p style='margin: 10px 0 0 0;'>System appears to be configured correctly.</p>";
    echo "</div>";
}

echo "</div>";

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<h2 style='color: #1e1e1e; margin-top: 0;'>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY AFTER USE!</strong></p>";
echo "<p>File: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
