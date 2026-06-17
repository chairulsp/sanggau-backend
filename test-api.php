<?php
/**
 * TEST API ENDPOINTS - Sanggau Backend
 * 
 * File ini test semua endpoint API yang digunakan oleh frontend
 * Upload ke root folder dan akses via browser
 * 
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Test API Endpoints</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h1 { color: #569cd6; }
    h2 { color: #4ec9b0; margin-top: 2rem; }
    pre { background: #252526; padding: 10px; overflow-x: auto; border-left: 3px solid #007acc; margin: 10px 0; }
    .endpoint { background: #252526; padding: 15px; margin: 15px 0; border-radius: 5px; }
    .endpoint-url { color: #ce9178; }
    .json-preview { max-height: 300px; overflow-y: auto; }
</style></head><body>";

echo "<h1>🧪 Test API Endpoints</h1>";
echo "<p>Testing all API endpoints used by frontend...</p>";
echo "<hr>";

// Test endpoints
$endpoints = [
    'Banner' => '/api/banner',
    'Berita' => '/api/berita',
    'Settings' => '/api/settings',
    'Pengumuman' => '/api/pengumuman',
    'Agenda' => '/api/agenda',
    'Profil Pimpinan' => '/api/profil-pimpinan',
    'Profil Diskominfo' => '/api/profil-diskominfo',
];

$baseUrl = 'https://api.diskominfo.sanggau.go.id';
$passed = 0;
$failed = 0;

foreach ($endpoints as $name => $path) {
    echo "<div class='endpoint'>";
    echo "<h2>Testing: $name</h2>";
    echo "<div class='endpoint-url'>GET $baseUrl$path</div>";
    
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: Sanggau-API-Tester/1.0'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            echo "<pre class='error'>❌ CURL ERROR: $curlError</pre>";
            $failed++;
            continue;
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            echo "<pre class='success'>✅ HTTP $httpCode - SUCCESS</pre>";
            $passed++;
            
            // Parse JSON
            $data = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Show preview
                $preview = $data;
                if (is_array($data) && count($data) > 0) {
                    $preview = array_slice($data, 0, 2); // Show first 2 items
                }
                echo "<pre class='info json-preview'>";
                echo htmlspecialchars(json_encode($preview, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                echo "</pre>";
                
                // Show count
                if (is_array($data)) {
                    echo "<div class='info'>📊 Total items: " . count($data) . "</div>";
                }
            } else {
                echo "<pre class='warning'>⚠️  Response is not valid JSON</pre>";
                echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
            }
        } else if ($httpCode === 404) {
            echo "<pre class='error'>❌ HTTP 404 - ENDPOINT NOT FOUND</pre>";
            $failed++;
        } else if ($httpCode === 500) {
            echo "<pre class='error'>❌ HTTP 500 - SERVER ERROR</pre>";
            echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
            $failed++;
        } else if ($httpCode === 0) {
            echo "<pre class='error'>❌ COULD NOT CONNECT TO SERVER</pre>";
            echo "<pre class='warning'>Server might be down or domain not accessible</pre>";
            $failed++;
        } else {
            echo "<pre class='warning'>⚠️  HTTP $httpCode</pre>";
            echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
            $failed++;
        }
        
    } catch (Exception $e) {
        echo "<pre class='error'>❌ EXCEPTION: " . $e->getMessage() . "</pre>";
        $failed++;
    }
    
    echo "</div>";
}

// Summary
echo "<hr>";
echo "<h2>📊 Test Summary</h2>";
echo "<pre>";
echo "<span class='success'>✅ Passed: $passed</span>\n";
echo "<span class='error'>❌ Failed: $failed</span>\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Total: " . count($endpoints) . " endpoints\n";
echo "</pre>";

if ($failed === 0) {
    echo "<pre class='success'>🎉 All API endpoints are working!</pre>";
} else {
    echo "<pre class='warning'>⚠️  Some endpoints failed. Check details above.</pre>";
    echo "<h2>🔍 Troubleshooting:</h2>";
    echo "<pre>";
    echo "1. Check if Laravel is running (visit $baseUrl directly)\n";
    echo "2. Check routes: php artisan route:list\n";
    echo "3. Check storage/logs/laravel.log for errors\n";
    echo "4. Verify database connection\n";
    echo "5. Check .htaccess and mod_rewrite\n";
    echo "</pre>";
}

// Test simple endpoint
echo "<hr>";
echo "<h2>🔗 Quick Test Links</h2>";
echo "<p>Click these links to test in browser:</p>";
echo "<ul>";
foreach ($endpoints as $name => $path) {
    $url = $baseUrl . $path;
    echo "<li><a href='$url' target='_blank' style='color:#9cdcfe'>$name</a></li>";
}
echo "</ul>";

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<h2 style='color: #1e1e1e; margin-top: 0;'>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY AFTER TESTING!</strong></p>";
echo "<p>File: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
