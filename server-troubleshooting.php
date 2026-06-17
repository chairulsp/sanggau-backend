<?php
/**
 * SANGGAU BACKEND - SERVER TROUBLESHOOTING TOOL
 * 
 * Upload file ini ke root folder di cPanel untuk troubleshoot masalah
 * Akses: https://api.diskominfo.sanggau.go.id/server-troubleshooting.php
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI TROUBLESHOOTING UNTUK KEAMANAN!
 */

// Cek apakah diakses dari browser
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/html; charset=utf-8');
}

echo "<html><head><title>Sanggau Backend - Troubleshooting</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .section { margin: 20px 0; padding: 15px; background: #252526; border-left: 3px solid #007acc; }
    h2 { color: #569cd6; }
    pre { background: #1e1e1e; padding: 10px; overflow-x: auto; }
</style></head><body>";

echo "<h1>🔧 Sanggau Backend - Troubleshooting Tool</h1>";
echo "<p>Waktu: " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// ========================================
// 1. PHP Info
// ========================================
echo "<div class='section'>";
echo "<h2>1. PHP Configuration</h2>";
echo "<pre>";
echo "PHP Version: <span class='success'>" . phpversion() . "</span>\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "</pre>";
echo "</div>";

// ========================================
// 2. Required PHP Extensions
// ========================================
echo "<div class='section'>";
echo "<h2>2. PHP Extensions</h2>";
echo "<pre>";
$required_extensions = [
    'pdo_mysql',
    'mbstring',
    'xml',
    'curl',
    'openssl',
    'tokenizer',
    'json',
    'fileinfo',
    'bcmath',
];

foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext);
    $symbol = $status ? '✅' : '❌';
    $class = $status ? 'success' : 'error';
    echo "$symbol <span class='$class'>$ext</span>\n";
}
echo "</pre>";
echo "</div>";

// ========================================
// 3. File/Folder Permissions
// ========================================
echo "<div class='section'>";
echo "<h2>3. File & Folder Permissions</h2>";
echo "<pre>";

$paths_to_check = [
    __DIR__ . '/.env',
    __DIR__ . '/storage',
    __DIR__ . '/storage/logs',
    __DIR__ . '/storage/framework',
    __DIR__ . '/bootstrap/cache',
];

foreach ($paths_to_check as $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -3);
        $writable = is_writable($path);
        $symbol = $writable ? '✅' : '⚠️';
        $class = $writable ? 'success' : 'warning';
        $type = is_dir($path) ? 'DIR' : 'FILE';
        echo "$symbol <span class='$class'>[$type] $path</span> → $perms\n";
    } else {
        echo "❌ <span class='error'>NOT FOUND: $path</span>\n";
    }
}
echo "</pre>";
echo "</div>";

// ========================================
// 4. .env File Check
// ========================================
echo "<div class='section'>";
echo "<h2>4. Environment Configuration</h2>";
echo "<pre>";

$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    echo "✅ <span class='success'>.env file exists</span>\n\n";
    
    // Parse .env without revealing sensitive data
    $env_content = file_get_contents($env_file);
    $lines = explode("\n", $env_content);
    
    $important_keys = [
        'APP_NAME',
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
        
        foreach ($important_keys as $key) {
            if (strpos($line, $key . '=') === 0) {
                // Mask password
                if (strpos($line, 'PASSWORD') !== false) {
                    echo "$key=<span class='warning'>***HIDDEN***</span>\n";
                } else {
                    echo "<span class='success'>$line</span>\n";
                }
                break;
            }
        }
        
        // Check if DB_PASSWORD is set (without showing it)
        if (strpos($line, 'DB_PASSWORD=') === 0) {
            $value = substr($line, strlen('DB_PASSWORD='));
            if (empty(trim($value))) {
                echo "<span class='error'>⚠️ DB_PASSWORD is EMPTY!</span>\n";
            } else {
                echo "DB_PASSWORD=<span class='warning'>***SET***</span>\n";
            }
        }
    }
} else {
    echo "❌ <span class='error'>.env file NOT FOUND!</span>\n";
    echo "<span class='warning'>Copy .env.example to .env and configure it!</span>\n";
}
echo "</pre>";
echo "</div>";

// ========================================
// 5. Database Connection Test
// ========================================
echo "<div class='section'>";
echo "<h2>5. Database Connection Test</h2>";
echo "<pre>";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        require __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        // Test database connection
        try {
            $db = $app->make('db');
            $pdo = $db->connection()->getPdo();
            
            echo "✅ <span class='success'>Database connected successfully!</span>\n";
            echo "Database name: <span class='success'>" . $db->connection()->getDatabaseName() . "</span>\n";
            echo "Driver: " . $db->connection()->getDriverName() . "\n";
            
            // Test query
            $result = $db->select('SELECT VERSION() as version');
            if (!empty($result)) {
                echo "MySQL Version: <span class='success'>" . $result[0]->version . "</span>\n";
            }
            
            // Check tables
            $tables = $db->select('SHOW TABLES');
            echo "Tables count: <span class='success'>" . count($tables) . "</span>\n";
            
        } catch (\Exception $e) {
            echo "❌ <span class='error'>Database connection FAILED!</span>\n";
            echo "<span class='error'>Error: " . $e->getMessage() . "</span>\n\n";
            
            echo "<span class='warning'>Common solutions:</span>\n";
            echo "1. Check DB credentials in .env file\n";
            echo "2. Use 'localhost' for DB_HOST (not 127.0.0.1)\n";
            echo "3. Verify database exists in cPanel MySQL Databases\n";
            echo "4. Check user has permissions to access database\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ <span class='error'>Laravel bootstrap failed!</span>\n";
        echo "<span class='error'>Error: " . $e->getMessage() . "</span>\n";
    }
} else {
    echo "❌ <span class='error'>Composer dependencies not installed!</span>\n";
    echo "<span class='warning'>Run: composer install</span>\n";
}

echo "</pre>";
echo "</div>";

// ========================================
// 6. Laravel Cache Status
// ========================================
echo "<div class='section'>";
echo "<h2>6. Laravel Cache Files</h2>";
echo "<pre>";

$cache_files = [
    'config' => __DIR__ . '/bootstrap/cache/config.php',
    'routes' => __DIR__ . '/bootstrap/cache/routes-v7.php',
    'services' => __DIR__ . '/bootstrap/cache/services.php',
];

foreach ($cache_files as $name => $file) {
    if (file_exists($file)) {
        $mtime = date('Y-m-d H:i:s', filemtime($file));
        echo "✅ <span class='success'>$name cache</span> (Modified: $mtime)\n";
    } else {
        echo "⚠️ <span class='warning'>$name cache not found (not cached)</span>\n";
    }
}

echo "\n<span class='warning'>If you changed .env, clear cache by running:</span>\n";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";

echo "</pre>";
echo "</div>";

// ========================================
// 7. Recent Laravel Logs
// ========================================
echo "<div class='section'>";
echo "<h2>7. Recent Laravel Logs</h2>";
echo "<pre>";

$log_file = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($log_file)) {
    echo "✅ <span class='success'>Log file exists</span>\n";
    echo "Size: " . number_format(filesize($log_file)) . " bytes\n";
    echo "Modified: " . date('Y-m-d H:i:s', filemtime($log_file)) . "\n\n";
    
    // Show last 30 lines
    $lines = file($log_file);
    $last_lines = array_slice($lines, -30);
    
    echo "<span class='warning'>Last 30 lines:</span>\n";
    echo "----------------------------------------\n";
    foreach ($last_lines as $line) {
        $line = htmlspecialchars($line);
        if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
            echo "<span class='error'>$line</span>";
        } else {
            echo $line;
        }
    }
} else {
    echo "⚠️ <span class='warning'>No log file found (or no errors yet)</span>\n";
}

echo "</pre>";
echo "</div>";

// ========================================
// Final Warning
// ========================================
echo "<div class='section' style='border-left-color: #f48771;'>";
echo "<h2 style='color: #f48771;'>⚠️ IMPORTANT SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY AFTER TROUBLESHOOTING!</strong></p>";
echo "<p>This file exposes sensitive system information.</p>";
echo "<p>File location: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
