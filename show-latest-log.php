<?php
/**
 * Show Latest Laravel Log Entries
 * Displays the most recent log entries to debug issues
 */

$logFile = __DIR__ . '/storage/logs/laravel.log';

echo "<h2>📋 Latest Laravel Logs</h2>";
echo "<pre style='background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 8px; overflow-x: auto;'>";

if (!file_exists($logFile)) {
    echo "❌ Log file not found: $logFile\n";
    echo "\nPossible reasons:\n";
    echo "1. No errors have been logged yet\n";
    echo "2. Storage folder is not writable\n";
    echo "3. Log file path is different\n";
    exit;
}

echo "✅ Log file found\n";
echo "Size: " . number_format(filesize($logFile)) . " bytes\n";
echo "Modified: " . date('Y-m-d H:i:s', filemtime($logFile)) . "\n\n";
echo str_repeat("=", 80) . "\n\n";

// Read last 100 lines
$lines = [];
$file = new SplFileObject($logFile, 'r');
$file->seek(PHP_INT_MAX);
$lastLine = $file->key();
$startLine = max(0, $lastLine - 100);

$file->seek($startLine);
while (!$file->eof()) {
    $lines[] = $file->fgets();
}

// Filter for recent entries (last 50 lines)
$recentLines = array_slice($lines, -50);

echo "📝 Last 50 lines:\n";
echo str_repeat("-", 80) . "\n\n";

foreach ($recentLines as $line) {
    // Color code different log levels
    $line = trim($line);
    if (empty($line)) continue;
    
    if (strpos($line, '.ERROR:') !== false) {
        echo "🔴 $line\n";
    } elseif (strpos($line, '.WARNING:') !== false) {
        echo "🟡 $line\n";
    } elseif (strpos($line, '.INFO:') !== false) {
        echo "🔵 $line\n";
    } else {
        echo "$line\n";
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "\n💡 Look for:\n";
echo "- 🔴 ERROR entries for actual errors\n";
echo "- 🔵 INFO 'Berita Store Request' for request data debugging\n";
echo "- Validation error messages\n";

echo "</pre>";
