<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🧹 Cleaning Up Secondary Debug Files</h2><pre>\n";

$filesToDelete = [
    'check_db_urls.php',
    'check_db_urls2.php',
    'check_db_urls3.php',
    'check_pegawai_storage.php',
    'fix_storage_link.php'
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
    echo "✅ Successfully cleaned up cleanup_all2.php itself.\n";
} else {
    echo "❌ Failed to delete cleanup_all2.php.\n";
}

echo "</pre>";
