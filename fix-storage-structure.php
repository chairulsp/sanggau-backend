<?php
/**
 * FIX STORAGE STRUCTURE - Sanggau Backend
 * 
 * Script ini membuat struktur folder storage yang dibutuhkan Laravel
 * Upload ke root folder dan akses via browser
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Fix Storage Structure</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    h1 { color: #569cd6; }
    pre { background: #252526; padding: 10px; }
</style></head><body>";

echo "<h1>🔧 Fix Storage Structure</h1>";
echo "<p>Creating Laravel storage folders...</p>";
echo "<hr>";
echo "<pre>";

$baseDir = __DIR__;
$created = 0;
$errors = 0;
$existed = 0;

// Define all required folders
$folders = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/testing',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($folders as $folder) {
    $path = $baseDir . '/' . $folder;
    
    if (file_exists($path)) {
        echo "✅ <span class='success'>EXISTS: $folder</span>\n";
        $existed++;
    } else {
        if (@mkdir($path, 0755, true)) {
            echo "✅ <span class='success'>CREATED: $folder</span>\n";
            $created++;
            
            // Create .gitignore in data folders
            if (in_array($folder, ['storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views'])) {
                file_put_contents($path . '/.gitignore', "*\n!.gitignore\n");
            }
        } else {
            echo "❌ <span class='error'>FAILED: $folder</span>\n";
            $errors++;
        }
    }
}

// Create index.html to prevent directory listing
$protectedFolders = ['storage', 'storage/app', 'storage/framework'];
foreach ($protectedFolders as $folder) {
    $indexPath = $baseDir . '/' . $folder . '/index.html';
    if (!file_exists($indexPath)) {
        file_put_contents($indexPath, '<!-- Directory access disabled -->');
    }
}

echo "\n<span class='success'>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>\n";
echo "<span class='success'>Summary:</span>\n";
echo "  Created: <span class='success'>$created</span> folders\n";
echo "  Existed: <span class='warning'>$existed</span> folders\n";
echo "  Errors:  <span class='error'>$errors</span> folders\n";

if ($errors > 0) {
    echo "\n<span class='error'>⚠️  Some folders failed to create!</span>\n";
    echo "<span class='warning'>Solution: Create them manually via File Manager with permission 755</span>\n";
} else {
    echo "\n<span class='success'>✅ All storage folders ready!</span>\n";
}

echo "\n<span class='warning'>Next steps:</span>\n";
echo "1. Clear Laravel cache (use clear-cache.php)\n";
echo "2. Test website\n";
echo "3. DELETE THIS FILE for security!\n";

echo "</pre>";

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px;'>";
echo "<h2>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY!</strong></p>";
echo "<p>File: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
