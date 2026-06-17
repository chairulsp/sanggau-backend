<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🧹 Cleaning Up Temporary Debug Files</h2><pre>\n";

$filesToDelete = [
    'debug_server.php',
    'debug2.php',
    'verify.php',
    'check_uploads_path.php',
    'scan_public_html.php',
    'copy_old_files.php',
    'fix_permissions_and_paths.php',
    'update_env.php'
];

foreach ($filesToDelete as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "✅ Deleted: $file\n";
        } else {
            echo "❌ Failed to delete: $file\n";
        }
    } else {
        echo "ℹ️ Already deleted or does not exist: $file\n";
    }
}

// Self-destruct
$self = __FILE__;
echo "\n=== Self-Destructing... ===\n";
if (unlink($self)) {
    echo "✅ Successfully cleaned up cleanup_all.php itself.\n";
} else {
    echo "❌ Failed to delete cleanup_all.php.\n";
}

echo "</pre>";
