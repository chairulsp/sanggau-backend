<?php
/**
 * Cleanup old files
 * Upload ke: public_html/cleanup.php
 * HAPUS setelah selesai!
 */

// Hapus folder public lama dan storage lama
function deleteDir($dir) {
    if (!is_dir($dir)) return false;
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = "$dir/$file";
        is_dir($path) ? deleteDir($path) : unlink($path);
    }
    return rmdir($dir);
}

echo "<h2>Cleanup</h2><pre>";

// Hapus folder public lama (yang 121 byte)
if (is_dir(__DIR__ . '/public')) {
    $result = deleteDir(__DIR__ . '/public');
    echo $result ? "✅ Folder public lama dihapus\n" : "❌ Gagal hapus folder public\n";
}

// Hapus folder storage lama
if (is_dir(__DIR__ . '/storage')) {
    $result = deleteDir(__DIR__ . '/storage');
    echo $result ? "✅ Folder storage lama dihapus\n" : "❌ Gagal hapus folder storage\n";
}

echo "\nSelesai! Sekarang upload ulang folder Laravel Anda.\n";
echo "</pre>";
echo "<p style='color:red'><strong>HAPUS file ini sekarang!</strong></p>";
