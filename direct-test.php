<?php
/**
 * DIRECT TEST - Bypass Laravel completely
 * Test database connection directly
 */

// Enable all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain; charset=utf-8');

echo "=== DIRECT PHP TEST ===\n\n";

// 1. Test PDO connection
echo "1. Testing PDO Connection...\n";
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=diskominfo_sanggaudb;charset=utf8mb4',
        'diskominfo_sanggau',
        'diskominfo_sanggau26',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ PDO Connected!\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM banners");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Banners count: " . $result['count'] . "\n\n";
    
} catch (PDOException $e) {
    echo "❌ PDO Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Test Laravel autoload
echo "2. Testing Laravel Autoload...\n";
try {
    require __DIR__ . '/vendor/autoload.php';
    echo "✅ Autoload OK\n\n";
} catch (Exception $e) {
    echo "❌ Autoload Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 3. Test Laravel bootstrap
echo "3. Testing Laravel Bootstrap...\n";
try {
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "✅ App created\n";
    
    // Check environment
    echo "   Environment: " . $app->environment() . "\n";
    echo "   Debug: " . ($app['config']['app.debug'] ? 'true' : 'false') . "\n\n";
    
} catch (Exception $e) {
    echo "❌ Bootstrap Failed: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n\n";
    exit(1);
}

// 4. Test DB binding
echo "4. Testing DB Service...\n";
try {
    if ($app->bound('db')) {
        echo "✅ DB is bound\n";
        $db = $app->make('db');
        echo "✅ DB instance created\n";
        
        $results = $db->select('SELECT * FROM banners LIMIT 1');
        echo "✅ Query works! Result:\n";
        print_r($results);
        
    } else {
        echo "❌ DB NOT bound in container\n";
        echo "   Trying manual register...\n";
        
        $app->register(\Illuminate\Database\DatabaseServiceProvider::class);
        echo "   ✅ Manually registered\n";
        
        if ($app->bound('db')) {
            echo "   ✅ Now DB is bound!\n";
            $db = $app->make('db');
            $results = $db->select('SELECT * FROM banners LIMIT 1');
            echo "   ✅ Query works!\n";
        } else {
            echo "   ❌ Still not bound after manual register\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ DB Test Failed: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "\n⚠️ DELETE THIS FILE AFTER READING!\n";
?>
