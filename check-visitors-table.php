<?php
/**
 * Check if visitors table exists
 * Upload to: /home/diskomi5/public_html/check-visitors-table.php
 * Access: https://diskominfo.sanggau.go.id/check-visitors-table.php
 */

require __DIR__.'/../laravel/vendor/autoload.php';

$app = require_once __DIR__.'/../laravel/bootstrap/app.php';

echo "<h2>Checking Visitors Table</h2>";
echo "<pre>";

try {
    // Get database connection
    $db = $app->make('db');
    
    // Check if table exists
    $tables = $db->select("SHOW TABLES LIKE 'visitors'");
    
    if (empty($tables)) {
        echo "❌ Table 'visitors' DOES NOT EXIST\n\n";
        echo "You need to run migration first!\n";
        echo "1. Upload migrate.php to public_html\n";
        echo "2. Access: https://diskominfo.sanggau.go.id/migrate.php\n";
    } else {
        echo "✅ Table 'visitors' EXISTS\n\n";
        
        // Get table structure
        echo "Table Structure:\n";
        echo "================\n";
        $columns = $db->select("DESCRIBE visitors");
        foreach ($columns as $col) {
            echo sprintf("%-20s %-15s %s\n", 
                $col->Field, 
                $col->Type, 
                $col->Null === 'YES' ? 'NULL' : 'NOT NULL'
            );
        }
        
        echo "\n";
        
        // Count records
        $count = $db->selectOne("SELECT COUNT(*) as total FROM visitors");
        echo "Total Records: " . $count->total . "\n\n";
        
        // Show last 5 records
        if ($count->total > 0) {
            echo "Last 5 Records:\n";
            echo "================\n";
            $records = $db->select("SELECT * FROM visitors ORDER BY created_at DESC LIMIT 5");
            foreach ($records as $rec) {
                echo "ID: {$rec->id}\n";
                echo "Session: {$rec->session_id}\n";
                echo "IP: {$rec->ip_address}\n";
                echo "Page: {$rec->halaman}\n";
                echo "Device: {$rec->device}\n";
                echo "Browser: {$rec->browser}\n";
                echo "Time: {$rec->created_at}\n";
                echo "---\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
echo "<hr>";
echo "<p><a href='/'>Back to Home</a></p>";
