<?php
/**
 * Test Visitor API Endpoints
 * Upload to: /home/diskomi5/public_html/test-visitor-api.php
 * Access: https://diskominfo.sanggau.go.id/test-visitor-api.php
 */

echo "<h2>Testing Visitor API Endpoints</h2>";
echo "<pre>";

$base_url = 'https://diskominfo.sanggau.go.id/api';

// Test 1: Track endpoint (POST)
echo "=================================\n";
echo "Test 1: POST /api/track\n";
echo "=================================\n";

$track_data = [
    'session_id' => 'test-session-' . time(),
    'halaman' => '/test',
    'referrer' => 'https://google.com'
];

$ch = curl_init($base_url . '/track');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($track_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $http_code\n";
echo "Response: $response\n\n";

if ($http_code === 200) {
    $json = json_decode($response, true);
    if (isset($json['ok']) && $json['ok'] === true) {
        echo "✅ Track endpoint is WORKING!\n";
    } else {
        echo "⚠️ Track endpoint responded but returned unexpected data\n";
    }
} else {
    echo "❌ Track endpoint FAILED!\n";
    echo "Possible issues:\n";
    echo "- Route not registered (check routes/api.php)\n";
    echo "- Controller not found (check VisitorController.php)\n";
    echo "- Database error (check table exists)\n";
}

echo "\n";

// Test 2: Stats endpoint (GET) - requires auth
echo "=================================\n";
echo "Test 2: GET /api/admin/visitor-stats\n";
echo "=================================\n";

$ch = curl_init($base_url . '/admin/visitor-stats');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $http_code\n";
echo "Response: " . substr($response, 0, 200) . "...\n\n";

if ($http_code === 401) {
    echo "✅ Stats endpoint exists (requires authentication)\n";
} elseif ($http_code === 200) {
    echo "⚠️ Stats endpoint is accessible without auth (security issue!)\n";
} else {
    echo "❌ Stats endpoint FAILED!\n";
    echo "Possible issues:\n";
    echo "- Route not registered\n";
    echo "- Middleware issue\n";
}

echo "\n";

// Test 3: Check routes
echo "=================================\n";
echo "Test 3: Check Route Registration\n";
echo "=================================\n";

require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';

try {
    $routes = app('router')->getRoutes();
    
    $visitor_routes = [];
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'track') !== false || strpos($uri, 'visitor') !== false) {
            $visitor_routes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $uri,
                'action' => $route->getActionName()
            ];
        }
    }
    
    if (empty($visitor_routes)) {
        echo "❌ NO visitor routes found!\n";
        echo "Check if routes/api.php was uploaded correctly.\n";
    } else {
        echo "✅ Found " . count($visitor_routes) . " visitor route(s):\n\n";
        foreach ($visitor_routes as $r) {
            echo sprintf("%-10s %-40s %s\n", $r['method'], $r['uri'], $r['action']);
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR checking routes: " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ul>";
echo "<li>If track endpoint failed: Run migration and clear cache</li>";
echo "<li>If routes not found: Re-upload routes/api.php and clear cache</li>";
echo "<li>If table doesn't exist: Upload and run migrate.php</li>";
echo "</ul>";

echo "<p><strong>Quick Actions:</strong></p>";
echo "<ul>";
echo "<li><a href='/check-visitors-table.php'>Check Database Table</a></li>";
echo "<li><a href='/migrate.php'>Run Migration</a></li>";
echo "<li><a href='/clearcache.php'>Clear Cache</a></li>";
echo "<li><a href='/'>Back to Home</a></li>";
echo "</ul>";
