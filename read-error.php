<?php
/**
 * READ LATEST ERROR - Show most recent Laravel errors
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Latest Error Log</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; font-size: 12px; line-height: 1.5; }
    .error { color: #f48771; background: #2d1f1f; padding: 20px; margin: 10px 0; border-left: 4px solid #f48771; white-space: pre-wrap; word-wrap: break-word; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h1 { color: #569cd6; }
    h2 { color: #4ec9b0; margin-top: 2rem; }
</style></head><body>";

echo "<h1>🔍 Latest Laravel Error Log</h1>";
echo "<p>Reading: storage/logs/laravel.log</p>";
echo "<hr>";

$logFile = __DIR__ . '/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "<div class='error'>❌ Log file not found: $logFile</div>";
    echo "</body></html>";
    exit;
}

// Read file
$content = file_get_contents($logFile);
$size = filesize($logFile);

echo "<h2>File Info</h2>";
echo "<p>Size: " . number_format($size) . " bytes</p>";
echo "<p>Last modified: " . date('Y-m-d H:i:s', filemtime($logFile)) . "</p>";

// Get last 2000 characters (should capture last error)
$tail = substr($content, -3000);

// Find last error/exception
$lines = explode("\n", $tail);
$errorLines = [];
$inError = false;

foreach ($lines as $line) {
    // Start of error entry
    if (preg_match('/\[\d{4}-\d{2}-\d{2}.*?\]\s+(local|production)\.(ERROR|EMERGENCY|ALERT|CRITICAL)/i', $line)) {
        $inError = true;
        $errorLines = [$line];
    } 
    // Continue collecting error lines
    elseif ($inError) {
        $errorLines[] = $line;
        // Stop at next log entry or empty line followed by timestamp
        if (preg_match('/^\[\d{4}-\d{2}-\d{2}/', $line) && count($errorLines) > 1) {
            break;
        }
    }
}

if (!empty($errorLines)) {
    echo "<h2>🔥 Most Recent Error</h2>";
    echo "<div class='error'>";
    echo htmlspecialchars(implode("\n", $errorLines));
    echo "</div>";
    
    // Try to extract key information
    $errorText = implode(' ', $errorLines);
    
    echo "<h2>💡 Quick Analysis</h2>";
    echo "<div style='background: #252526; padding: 15px; margin: 10px 0;'>";
    
    // Common error patterns
    if (stripos($errorText, 'Target class [db] does not exist') !== false) {
        echo "<p class='error'>❌ <strong>Database service still not registered</strong></p>";
        echo "<p class='warning'>Issue: AppServiceProvider fix didn't work or wasn't uploaded properly</p>";
        echo "<p class='info'>Solution: Verify app/Providers/AppServiceProvider.php was uploaded and has the register() method fix</p>";
    }
    elseif (stripos($errorText, 'Class') !== false && stripos($errorText, 'not found') !== false) {
        preg_match('/Class [\'"]?([^\'"\\s]+)[\'"]? not found/i', $errorText, $matches);
        $className = $matches[1] ?? 'Unknown';
        echo "<p class='error'>❌ <strong>Class not found: $className</strong></p>";
        echo "<p class='warning'>Issue: Composer autoload cache or missing dependency</p>";
        echo "<p class='info'>Solution: Need to run 'composer dump-autoload' on server</p>";
    }
    elseif (stripos($errorText, 'syntax error') !== false || stripos($errorText, 'unexpected') !== false) {
        echo "<p class='error'>❌ <strong>PHP Syntax Error</strong></p>";
        echo "<p class='warning'>Issue: PHP syntax error in uploaded file</p>";
        echo "<p class='info'>Solution: Check the file mentioned in error for syntax errors</p>";
    }
    elseif (stripos($errorText, 'SQLSTATE') !== false) {
        echo "<p class='error'>❌ <strong>Database Error</strong></p>";
        echo "<p class='warning'>Issue: SQL/Database connection or query error</p>";
        echo "<p class='info'>Solution: Check database credentials and connection</p>";
    }
    elseif (stripos($errorText, 'file_put_contents') !== false || stripos($errorText, 'Permission denied') !== false) {
        echo "<p class='error'>❌ <strong>Permission Error</strong></p>";
        echo "<p class='warning'>Issue: Cannot write to storage folder</p>";
        echo "<p class='info'>Solution: Set storage/ permissions to 755/775</p>";
    }
    elseif (stripos($errorText, 'cache path') !== false) {
        echo "<p class='error'>❌ <strong>Cache Path Error</strong></p>";
        echo "<p class='warning'>Issue: storage/framework folders missing or not writable</p>";
        echo "<p class='info'>Solution: Run fix-storage-structure.php again</p>";
    }
    else {
        echo "<p class='warning'>⚠️ Custom error - read the full error message above for details</p>";
    }
    
    echo "</div>";
    
} else {
    echo "<h2>Last 50 Lines of Log</h2>";
    $lastLines = array_slice($lines, -50);
    echo "<div class='error'>";
    echo htmlspecialchars(implode("\n", $lastLines));
    echo "</div>";
}

// Show ALL errors from last 100 lines
echo "<h2>📜 Context (Last 100 Lines)</h2>";
echo "<details><summary>Click to expand</summary>";
echo "<div style='background: #252526; padding: 15px; margin: 10px 0; max-height: 500px; overflow-y: auto;'>";
$lastLines = array_slice(explode("\n", $content), -100);
foreach ($lastLines as $line) {
    if (stripos($line, 'ERROR') !== false || stripos($line, 'EXCEPTION') !== false) {
        echo "<span class='error'>" . htmlspecialchars($line) . "</span>\n";
    } else {
        echo "<span class='info'>" . htmlspecialchars($line) . "</span>\n";
    }
}
echo "</div>";
echo "</details>";

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; border-radius: 5px;'>";
echo "<strong>⚠️ DELETE THIS FILE AFTER READING!</strong><br>";
echo "File: <code>" . __FILE__ . "</code>";
echo "</div>";

echo "</body></html>";
?>
