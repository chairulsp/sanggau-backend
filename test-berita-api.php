<?php
/**
 * Test Berita API Endpoints
 * Check if berita data is being returned correctly
 */

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🧪 Test Berita API Endpoints</h2>";
echo "<style>
pre { background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 8px; overflow-x: auto; }
.success { color: #4ade80; }
.error { color: #f87171; }
.warning { color: #fbbf24; }
</style>";

// Test 1: Direct Database Query
echo "<h3>1. Direct Database Query</h3>";
echo "<pre>";

try {
    require __DIR__ . '/vendor/autoload.php';
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    // Query without relationships first
    $beritas = \App\Models\Berita::latest()->get();
    
    echo "✅ <span class='success'>Query Success!</span>\n";
    echo "Total berita in database: <strong>" . $beritas->count() . "</strong>\n\n";
    
    if ($beritas->count() > 0) {
        echo "Latest berita:\n";
        echo str_repeat("-", 80) . "\n";
        foreach ($beritas->take(5) as $b) {
            echo "ID: {$b->id}\n";
            echo "Judul: {$b->judul}\n";
            echo "Kategori: {$b->kategori}\n";
            echo "Status: " . ($b->aktif ? "Published ✅" : "Draft 📝") . "\n";
            echo "User ID: {$b->user_id}\n";
            echo "Created: {$b->created_at}\n";
            echo str_repeat("-", 80) . "\n";
        }
    } else {
        echo "<span class='warning'>⚠️ No berita found in database</span>\n";
    }
} catch (\Exception $e) {
    echo "❌ <span class='error'>Error:</span> " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "</pre>";

// Test 2: API Endpoint Test
echo "<h3>2. Public Berita API Endpoint</h3>";
echo "<pre>";

$publicApiUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
          . "://{$_SERVER['HTTP_HOST']}/api/berita";

echo "Testing PUBLIC endpoint: <span class='warning'>$publicApiUrl</span>\n";
echo "(Admin endpoint requires authentication)\n\n";

// Check if we can make a curl request
if (function_exists('curl_init')) {
    $ch = curl_init($publicApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Status: <strong>$httpCode</strong>\n\n";
    
    if ($httpCode === 200) {
        echo "✅ <span class='success'>API Response OK!</span>\n\n";
        
        $data = json_decode($response, true);
        if (is_array($data)) {
            echo "Total berita returned: <strong>" . count($data) . "</strong>\n\n";
            
            if (count($data) > 0) {
                echo "First berita:\n";
                echo json_encode($data[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        } else {
            echo "<span class='warning'>⚠️ Response is not an array</span>\n";
            echo substr($response, 0, 500) . "\n";
        }
    } else {
        echo "❌ <span class='error'>API Error</span>\n\n";
        echo substr($response, 0, 1000) . "\n";
    }
} else {
    echo "<span class='warning'>⚠️ curl not available</span>\n";
}

echo "</pre>";

// Test 3: Check for b64: encoded data
echo "<h3>3. Check for Base64 Encoded Data</h3>";
echo "<pre>";

try {
    $beritas = \App\Models\Berita::latest()->take(10)->get();
    
    $hasB64 = false;
    foreach ($beritas as $b) {
        if (strpos($b->judul, 'b64:') === 0) {
            $hasB64 = true;
            echo "⚠️ <span class='warning'>Found b64 encoded judul:</span>\n";
            echo "ID: {$b->id}\n";
            echo "Judul (encoded): {$b->judul}\n";
            
            // Try to decode
            $decoded = base64_decode(substr($b->judul, 4), true);
            if ($decoded !== false) {
                echo "Judul (decoded): {$decoded}\n";
            }
            echo "\n";
        }
        
        if (strpos($b->konten, 'b64:') !== false) {
            echo "⚠️ <span class='warning'>Found b64 in konten for ID {$b->id}</span>\n\n";
        }
    }
    
    if (!$hasB64) {
        echo "✅ <span class='success'>No base64 encoded data found!</span>\n";
    } else {
        echo "\n<span class='error'>💡 Solution: Delete these test berita and create new ones after uploading fixed middleware.</span>\n";
    }
} catch (\Exception $e) {
    echo "❌ <span class='error'>Error:</span> " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Upload fixed <code>DecodeBase64Input.php</code> and <code>Kernel.php</code></li>";
echo "<li>Clear cache: <a href='/clear-cache.php'>clear-cache.php</a></li>";
echo "<li>Delete test berita dengan judul 'b64:...'</li>";
echo "<li>Create new berita - should work properly now!</li>";
echo "</ol>";
