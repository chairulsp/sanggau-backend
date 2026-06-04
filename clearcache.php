<?php
/**
 * Clear Laravel Cache Helper
 * Upload to: /home/diskomi5/public_html/clearcache.php
 * Access: https://diskominfo.sanggau.go.id/clearcache.php
 * DELETE this file after use!
 */

require __DIR__.'/../laravel/vendor/autoload.php';
$app    = require_once __DIR__.'/../laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Clearing Laravel Cache...</h2><pre>";

$commands = [
    'config:clear'   => 'Config cache',
    'route:clear'    => 'Route cache',
    'cache:clear'    => 'Application cache',
    'view:clear'     => 'View cache',
    'event:clear'    => 'Event cache',
];

foreach ($commands as $cmd => $label) {
    try {
        $kernel->call($cmd);
        echo "✅ {$label} cleared\n";
    } catch (Exception $e) {
        echo "⚠️  {$label}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ All caches cleared successfully!\n";
echo "\n⚠️  DELETE this file after use for security!\n";
echo "</pre>";
echo "<p><a href='/'>Back to Home</a></p>";
