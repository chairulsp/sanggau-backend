<?php
/**
 * Check Middleware Configuration
 * Verifies that problematic middleware is disabled
 */

require __DIR__ . '/vendor/autoload.php';

echo "<h2>🔍 Middleware Configuration Check</h2>";
echo "<pre>";

// Check if Kernel.php exists and is readable
$kernelPath = __DIR__ . '/app/Http/Kernel.php';
if (!file_exists($kernelPath)) {
    echo "❌ Kernel.php not found!\n";
    exit;
}

echo "✅ Kernel.php found\n\n";

// Read Kernel.php content
$kernelContent = file_get_contents($kernelPath);

echo "Checking for problematic middleware:\n\n";

// Check 1: Fruitcake\Cors\HandleCors
if (strpos($kernelContent, "\\Fruitcake\\Cors\\HandleCors::class") !== false) {
    if (strpos($kernelContent, "// \\Fruitcake\\Cors\\HandleCors::class") !== false) {
        echo "✅ Fruitcake\Cors is COMMENTED OUT (good)\n";
    } else {
        echo "❌ Fruitcake\Cors is ACTIVE (BAD - will cause 500 error)\n";
    }
} else {
    echo "✅ Fruitcake\Cors not found in Kernel.php\n";
}

// Check 2: DecodeBase64Input in global middleware
if (strpos($kernelContent, "\\App\\Http\\Middleware\\DecodeBase64Input::class") !== false) {
    // Count occurrences
    preg_match_all('/DecodeBase64Input::class/', $kernelContent, $matches);
    $count = count($matches[0]);
    
    // Check if all are commented
    preg_match_all('/\/\/.*DecodeBase64Input::class/', $kernelContent, $commentedMatches);
    $commentedCount = count($commentedMatches[0]);
    
    echo "\nDecodeBase64Input found $count times\n";
    echo "Commented out: $commentedCount times\n";
    
    if ($count === $commentedCount) {
        echo "✅ All DecodeBase64Input middleware are DISABLED (good)\n";
    } else {
        echo "❌ Some DecodeBase64Input middleware are ACTIVE (BAD)\n";
        
        // Show which sections have it active
        $lines = explode("\n", $kernelContent);
        foreach ($lines as $num => $line) {
            if (strpos($line, "DecodeBase64Input") !== false && strpos($line, "//") === false) {
                echo "   Line " . ($num + 1) . ": " . trim($line) . "\n";
            }
        }
    }
} else {
    echo "✅ DecodeBase64Input not found in Kernel.php\n";
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// Show API middleware group
echo "API Middleware Group:\n";
echo str_repeat("-", 60) . "\n";
preg_match("/'api'\s*=>\s*\[(.*?)\]/s", $kernelContent, $apiMiddleware);
if (isset($apiMiddleware[1])) {
    $middlewares = array_filter(array_map('trim', explode("\n", $apiMiddleware[1])));
    foreach ($middlewares as $mw) {
        if (empty($mw) || $mw === '[' || $mw === ']' || $mw === ',') continue;
        
        $isCommented = strpos($mw, '//') !== false;
        $icon = $isCommented ? '⚪' : '🟢';
        echo "$icon $mw\n";
    }
} else {
    echo "Could not parse API middleware group\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "\n✅ Middleware check complete!\n";
echo "\nIf you see any ❌ or 🟢 on DecodeBase64Input, you need to upload the fixed Kernel.php\n";
echo "</pre>";
