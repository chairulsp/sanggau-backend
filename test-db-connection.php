<?php
/**
 * DATABASE CONNECTION TESTER - Sanggau Backend
 * 
 * Bootstraps Laravel correctly and tests the database connection.
 * Upload to: public_html/test-db-connection.php
 * Access: https://api.diskominfo.sanggau.go.id/test-db-connection.php
 * 
 * ⚠️ DELETE THIS FILE AFTER USE!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Database Connection Tester</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h1 { color: #569cd6; }
    pre { background: #252526; padding: 10px; overflow-x: auto; border-left: 3px solid #007acc; }
</style></head><body>";

echo "<h1>🧪 Database Connection Tester</h1>";
echo "<p>Bootstrapping Laravel and testing MySQL connection...</p>";
echo "<hr><pre>";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        echo "Loading autoload.php...\n";
        require __DIR__ . '/vendor/autoload.php';
        
        echo "Loading bootstrap/app.php...\n";
        $app = require_once __DIR__ . '/bootstrap/app.php';
        
        echo "Bootstrapping Application via Console Kernel...\n";
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        echo "Retrieving 'db' instance...\n";
        $db = $app->make('db');
        
        echo "Attempting to connect...\n";
        $pdo = $db->connection()->getPdo();
        
        echo "\n✅ <span class='success'>Database connected successfully!</span>\n";
        echo "Database Name: <span class='success'>" . $db->connection()->getDatabaseName() . "</span>\n";
        echo "Driver Name: " . $db->connection()->getDriverName() . "\n";
        
        // Test query
        $result = $db->select('SELECT VERSION() as version');
        if (!empty($result)) {
            echo "MySQL Version: <span class='success'>" . $result[0]->version . "</span>\n";
        }
        
        // Show tables count
        $tables = $db->select('SHOW TABLES');
        echo "Tables Count: <span class='success'>" . count($tables) . "</span>\n";
        
        if (count($tables) > 0) {
            echo "\nSample Tables:\n";
            $i = 0;
            foreach ($tables as $table) {
                $arr = (array)$table;
                $tableName = reset($arr);
                echo " - " . $tableName . "\n";
                $i++;
                if ($i >= 5) {
                    echo " - ... and " . (count($tables) - 5) . " more tables\n";
                    break;
                }
            }
        }

        echo "\n=== HTTP Kernel Middleware Check ===\n";
        echo "Instantiating HTTP Kernel...\n";
        $httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $reflector = new ReflectionClass(get_class($httpKernel));
        $middlewareProp = $reflector->getProperty('middleware');
        $middlewareProp->setAccessible(true);
        $middleware = $middlewareProp->getValue($httpKernel);
        
        echo "Global Middleware registered on Server:\n";
        foreach ($middleware as $mw) {
            echo " - " . $mw . "\n";
        }
        
        
    } catch (\Throwable $e) {
        echo "\n❌ <span class='error'>Database connection or bootstrap FAILED!</span>\n";
        echo "Error: <span class='error'>" . $e->getMessage() . "</span>\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ <span class='error'>vendor/autoload.php not found!</span>\n";
    echo "Are you sure this script is in the Laravel root directory on the server?\n";
}

echo "</pre><hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; border-radius: 5px;'>";
echo "<strong>⚠️ SECURITY WARNING: DELETE THIS FILE IMMEDIATELY!</strong><br>";
echo "File: <code>" . __FILE__ . "</code>";
echo "</div></body></html>";
