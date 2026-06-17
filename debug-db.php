<?php
/**
 * DEBUG DATABASE SERVICE - Deep Investigation
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Debug DB Service</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; font-size: 13px; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h2 { color: #4ec9b0; margin-top: 2rem; }
    pre { background: #252526; padding: 15px; border-left: 3px solid #007acc; line-height: 1.6; overflow-x: auto; }
</style></head><body>";

echo "<h1>🔍 Debug Database Service</h1>";
echo "<hr>";

// ═══════════════════════════════════════════════════════════════════════════
// 1. Check PDO Extension
// ═══════════════════════════════════════════════════════════════════════════
echo "<h2>1️⃣ PDO Extensions</h2>";
echo "<pre>";
$pdo_mysql = extension_loaded('pdo_mysql');
$pdo = extension_loaded('pdo');
echo "PDO: " . ($pdo ? '<span class="success">✅ Loaded</span>' : '<span class="error">❌ Not Loaded</span>') . "\n";
echo "PDO MySQL: " . ($pdo_mysql ? '<span class="success">✅ Loaded</span>' : '<span class="error">❌ Not Loaded</span>') . "\n";
echo "</pre>";

if (!$pdo_mysql) {
    echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong>CRITICAL: PDO MySQL extension not loaded!</strong><br>";
    echo "Enable it in PHP configuration.";
    echo "</div>";
}

// ═══════════════════════════════════════════════════════════════════════════
// 2. Direct PDO Test
// ═══════════════════════════════════════════════════════════════════════════
echo "<h2>2️⃣ Direct PDO Connection Test</h2>";
echo "<pre>";

// Read .env
$envFile = __DIR__ . '/.env';
$envVars = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value);
        }
    }
}

$host = $envVars['DB_HOST'] ?? 'localhost';
$db = $envVars['DB_DATABASE'] ?? '';
$user = $envVars['DB_USERNAME'] ?? '';
$pass = $envVars['DB_PASSWORD'] ?? '';

echo "Attempting connection with:\n";
echo "Host: <span class='info'>$host</span>\n";
echo "Database: <span class='info'>$db</span>\n";
echo "Username: <span class='info'>$user</span>\n";
echo "Password: <span class='warning'>" . (empty($pass) ? '(empty)' : '***SET***') . "</span>\n\n";

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ <span class='success'>Direct PDO connection SUCCESSFUL!</span>\n\n";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version, DATABASE() as db");
    $result = $stmt->fetch();
    echo "MySQL Version: <span class='success'>{$result['version']}</span>\n";
    echo "Current DB: <span class='success'>{$result['db']}</span>\n\n";
    
    // List tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables (" . count($tables) . "):\n";
    foreach (array_slice($tables, 0, 10) as $table) {
        echo "  - <span class='info'>$table</span>\n";
    }
    if (count($tables) > 10) {
        echo "  ... and " . (count($tables) - 10) . " more\n";
    }
    
} catch (PDOException $e) {
    echo "❌ <span class='error'>Direct PDO connection FAILED!</span>\n";
    echo "<span class='error'>Error: " . $e->getMessage() . "</span>\n\n";
    echo "<span class='warning'>Common causes:</span>\n";
    echo "1. Wrong credentials\n";
    echo "2. Database doesn't exist\n";
    echo "3. User doesn't have permissions\n";
    echo "4. MySQL server not running\n";
}

echo "</pre>";

// ═══════════════════════════════════════════════════════════════════════════
// 3. Laravel Bootstrap Test
// ═══════════════════════════════════════════════════════════════════════════
echo "<h2>3️⃣ Laravel Bootstrap</h2>";
echo "<pre>";

try {
    require __DIR__ . '/vendor/autoload.php';
    echo "✅ <span class='success'>Autoload successful</span>\n";
    
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "✅ <span class='success'>App bootstrap successful</span>\n";
    
    // Check if app has db binding
    $hasDb = $app->bound('db');
    echo "App has 'db' binding: " . ($hasDb ? '<span class="success">✅ Yes</span>' : '<span class="error">❌ No</span>') . "\n";
    
    // Try to make db
    try {
        $db = $app->make('db');
        echo "✅ <span class='success'>DB instance created via make('db')</span>\n";
        
        // Try to get connection
        try {
            $connection = $db->connection();
            echo "✅ <span class='success'>DB connection retrieved</span>\n";
            
            // Try to get PDO
            try {
                $pdo = $connection->getPdo();
                echo "✅ <span class='success'>PDO instance retrieved</span>\n";
                
                $dbName = $connection->getDatabaseName();
                echo "✅ <span class='success'>Connected to database: $dbName</span>\n";
                
            } catch (\Exception $e) {
                echo "❌ <span class='error'>getPdo() failed: " . $e->getMessage() . "</span>\n";
            }
        } catch (\Exception $e) {
            echo "❌ <span class='error'>connection() failed: " . $e->getMessage() . "</span>\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ <span class='error'>make('db') failed: " . $e->getMessage() . "</span>\n";
        echo "\n<span class='warning'>Trying alternative method...</span>\n\n";
        
        // Try DB facade
        try {
            $db = app('db');
            echo "✅ <span class='success'>DB via app('db') works!</span>\n";
        } catch (\Exception $e2) {
            echo "❌ <span class='error'>app('db') also failed: " . $e2->getMessage() . "</span>\n";
        }
        
        // Try DatabaseManager
        try {
            $db = app(Illuminate\Database\DatabaseManager::class);
            echo "✅ <span class='success'>DatabaseManager works!</span>\n";
        } catch (\Exception $e3) {
            echo "❌ <span class='error'>DatabaseManager failed: " . $e3->getMessage() . "</span>\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ <span class='error'>Bootstrap failed: " . $e->getMessage() . "</span>\n";
    echo "\n<span class='error'>Stack trace:</span>\n";
    echo "<span class='warning'>" . $e->getTraceAsString() . "</span>\n";
}

echo "</pre>";

// ═══════════════════════════════════════════════════════════════════════════
// 4. Check Service Providers
// ═══════════════════════════════════════════════════════════════════════════
echo "<h2>4️⃣ Service Providers</h2>";
echo "<pre>";

$configApp = __DIR__ . '/config/app.php';
if (file_exists($configApp)) {
    $config = require $configApp;
    $providers = $config['providers'] ?? [];
    
    $dbProvider = 'Illuminate\Database\DatabaseServiceProvider::class';
    $hasDbProvider = false;
    
    foreach ($providers as $provider) {
        if (strpos($provider, 'DatabaseServiceProvider') !== false) {
            echo "✅ <span class='success'>Found: $provider</span>\n";
            $hasDbProvider = true;
        }
    }
    
    if (!$hasDbProvider) {
        echo "❌ <span class='error'>DatabaseServiceProvider NOT FOUND in config/app.php!</span>\n";
    }
} else {
    echo "❌ <span class='error'>config/app.php not found!</span>\n";
}

echo "</pre>";

// ═══════════════════════════════════════════════════════════════════════════
// 5. Check config/database.php
// ═══════════════════════════════════════════════════════════════════════════
echo "<h2>5️⃣ Database Configuration</h2>";
echo "<pre>";

$configDb = __DIR__ . '/config/database.php';
if (file_exists($configDb)) {
    echo "✅ <span class='success'>config/database.php exists</span>\n";
    
    // Try to load config
    try {
        $dbConfig = require $configDb;
        $default = $dbConfig['default'] ?? 'mysql';
        echo "Default connection: <span class='info'>$default</span>\n";
        
        if (isset($dbConfig['connections'][$default])) {
            $conn = $dbConfig['connections'][$default];
            echo "\nConnection config:\n";
            echo "  Driver: <span class='info'>{$conn['driver']}</span>\n";
            echo "  Host: <span class='info'>" . ($conn['host'] ?? 'N/A') . "</span>\n";
            echo "  Database: <span class='info'>" . ($conn['database'] ?? 'N/A') . "</span>\n";
        }
    } catch (\Exception $e) {
        echo "⚠️  <span class='warning'>Could not parse config: " . $e->getMessage() . "</span>\n";
    }
} else {
    echo "❌ <span class='error'>config/database.php NOT FOUND!</span>\n";
}

echo "</pre>";

// ═══════════════════════════════════════════════════════════════════════════
// SUMMARY & SOLUTION
// ═══════════════════════════════════════════════════════════════════════════
echo "<hr>";
echo "<h2>💡 Solution</h2>";
echo "<pre>";

if ($pdo_mysql && isset($pdo) && $pdo instanceof PDO) {
    echo "<span class='success'>✅ Direct PDO works but Laravel 'db' service fails!</span>\n\n";
    echo "<span class='warning'>This means:</span>\n";
    echo "1. Database credentials are CORRECT\n";
    echo "2. MySQL connection works\n";
    echo "3. Laravel service container issue\n\n";
    echo "<span class='info'>SOLUTION: Try regenerating composer autoload</span>\n";
    echo "Run via SSH: <span class='warning'>composer dump-autoload</span>\n";
    echo "Or re-run: <span class='warning'>composer install --no-dev</span>\n";
} else {
    echo "<span class='error'>❌ Direct PDO also fails</span>\n\n";
    echo "<span class='warning'>Fix database credentials first before troubleshooting Laravel.</span>\n";
}

echo "</pre>";

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<h2 style='color: #1e1e1e; margin-top: 0;'>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY!</strong></p>";
echo "<p>File: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
