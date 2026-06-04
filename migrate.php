<?php
/**
 * Migration Helper for Shared Hosting
 * Upload this file to /home/diskomi5/public_html/migrate.php
 * Access: https://diskominfo.sanggau.go.id/migrate.php
 */

// Security: Only allow from specific IPs or add password protection
// Uncomment and set your IP:
// $allowed_ips = ['YOUR_IP_HERE'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

require __DIR__.'/../laravel/vendor/autoload.php';

$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Running Database Migrations</h2>";
echo "<pre>";

try {
    // Run migrations
    $status = $kernel->call('migrate', [
        '--force' => true,  // Required for production
        '--verbose' => true
    ]);
    
    echo "\n\n";
    echo "=================================\n";
    echo "Migration Status: " . ($status === 0 ? 'SUCCESS' : 'FAILED') . "\n";
    echo "=================================\n";
    
    if ($status === 0) {
        echo "\n✅ All migrations completed successfully!\n";
        echo "\nNext steps:\n";
        echo "1. Clear cache: https://diskominfo.sanggau.go.id/clearcache.php\n";
        echo "2. Test API: https://diskominfo.sanggau.go.id/api/track\n";
        echo "3. DELETE this file for security!\n";
    } else {
        echo "\n❌ Migration failed. Check error messages above.\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Delete this file after migration for security!</p>";
echo "<p><a href='/'>Back to Home</a></p>";
