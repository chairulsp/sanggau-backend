<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "<h2>🔗 Symbolic Link (Symlink) Fixer</h2><pre>\n";

$publicHtmlDir = __DIR__;
$laravelDir = realpath(__DIR__ . '/../laravel');

$linkPath = $publicHtmlDir . '/storage';
$targetPath = $laravelDir . '/storage/app/public';

echo "Link Path (Webroot):   $linkPath\n";
echo "Target Path (Laravel): $targetPath\n\n";

// 1. Check if target exists
if (!is_dir($targetPath)) {
    die("❌ Target directory $targetPath does not exist. Cannot create link.\n");
}

// 2. Handle existing public_html/storage
if (file_exists($linkPath) || is_link($linkPath)) {
    if (is_link($linkPath)) {
        echo "ℹ️ existing path is already a symlink. Removing it to recreate...\n";
        if (unlink($linkPath)) {
            echo "✅ Removed old symlink.\n";
        } else {
            die("❌ Failed to remove old symlink.\n");
        }
    } else if (is_dir($linkPath)) {
        echo "⚠️ Existing path is a REAL directory! Renaming it to 'storage_backup' to make way...\n";
        $backupPath = $publicHtmlDir . '/storage_backup_' . time();
        if (rename($linkPath, $backupPath)) {
            echo "✅ Renamed existing storage directory to: " . basename($backupPath) . "\n";
        } else {
            die("❌ Failed to rename existing directory.\n");
        }
    }
}

// 3. Create symlink
echo "\n=== Creating Symlink ===\n";
if (symlink($targetPath, $linkPath)) {
    echo "✅ Success! Symlink created successfully.\n";
} else {
    echo "❌ Failed to create symlink.\n";
}

// 4. Verify symlink
echo "\n=== Verifying Symlink ===\n";
if (is_link($linkPath)) {
    echo "✅ Link exists!\n";
    echo "  - Target: " . readlink($linkPath) . "\n";
    
    // Check if we can read a file through it
    $testFile = $linkPath . '/pegawai/1780808096_pak_joni.png';
    if (file_exists($testFile)) {
        echo "✅ Verification SUCCESS! Can read 'pegawai/1780808096_pak_joni.png' through the new symlink.\n";
    } else {
        echo "❌ Verification FAILED. Cannot read file through symlink.\n";
    }
} else {
    echo "❌ Verification FAILED. Path is not a link.\n";
}

echo "\n</pre>";
