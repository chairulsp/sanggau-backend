<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Production .env Updater</h2><pre>\n";

$laravelDir = realpath(__DIR__ . '/../laravel');
$envFile = $laravelDir . '/.env';

echo "Laravel Path: $laravelDir\n";
echo ".env Path:     $envFile\n\n";

if (!file_exists($envFile)) {
    die("❌ .env file not found at $envFile\n");
}

$envContent = file_get_contents($envFile);

// 1. Update APP_URL
$oldUrl = 'APP_URL=https://diskominfo.sanggau.go.id';
$newUrl = 'APP_URL=https://api.diskominfo.sanggau.go.id';

if (strpos($envContent, $oldUrl) !== false) {
    $envContent = str_replace($oldUrl, $newUrl, $envContent);
    if (file_put_contents($envFile, $envContent) !== false) {
        echo "✅ Updated APP_URL to https://api.diskominfo.sanggau.go.id in .env\n";
    } else {
        echo "❌ Failed to write to .env\n";
    }
} else if (strpos($envContent, $newUrl) !== false) {
    echo "ℹ️ APP_URL is already set to https://api.diskominfo.sanggau.go.id\n";
} else {
    echo "⚠️ APP_URL line not found or matches a different URL. Let's inspect .env content safely:\n";
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos($line, 'APP_URL=') === 0) {
            echo "Current line: " . htmlspecialchars($line) . "\n";
            // Replace whatever APP_URL is
            $envContent = preg_replace('/^APP_URL=.*/m', $newUrl, $envContent);
            if (file_put_contents($envFile, $envContent) !== false) {
                echo "✅ Force updated APP_URL line to: $newUrl\n";
            } else {
                echo "❌ Failed to force update APP_URL in .env\n";
            }
        }
    }
}

// 2. Clear config cache
echo "\n=== 2. Clearing Laravel config cache ===\n";
$configCache = $laravelDir . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    if (unlink($configCache)) {
        echo "✅ Deleted config cache file: config.php\n";
    } else {
        echo "❌ Failed to delete config cache file.\n";
    }
} else {
    echo "ℹ️ Config cache file does not exist (already cleared).\n";
}

echo "\n</pre>";
