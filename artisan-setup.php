<?php
/**
 * Setup Helper - jalankan setelah upload Laravel
 * Upload ke: /home/diskominfo/public_html/artisan-setup.php
 * Akses: https://diskominfo.sanggau.go.id/artisan-setup.php
 * HAPUS file ini setelah selesai!
 */

require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Laravel Setup</h2><pre>";

$commands = [
    ['migrate', ['--force' => true]],
    ['config:clear', []],
    ['route:clear', []],
    ['cache:clear', []],
    ['view:clear', []],
    ['storage:link', []],
];

foreach ($commands as [$cmd, $args]) {
    try {
        $kernel->call($cmd, $args);
        echo "✅ {$cmd}\n";
    } catch (Exception $e) {
        echo "⚠️  {$cmd}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Setup selesai!\n";
echo "⚠️  HAPUS file ini sekarang!\n";
echo "</pre>";
