<?php
/**
 * CHECK ROUTES - Find Duplicate Route Names
 * 
 * Upload ke root folder dan akses via browser
 * ⚠️ HAPUS FILE INI SETELAH SELESAI!
 */

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>Check Routes</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
    .success { color: #4ec9b0; }
    .error { color: #f48771; }
    .warning { color: #dcdcaa; }
    .info { color: #9cdcfe; }
    h1 { color: #569cd6; }
    pre { background: #252526; padding: 15px; overflow-x: auto; border-left: 3px solid #007acc; }
    .duplicate { background: #f48771; color: #1e1e1e; padding: 10px; margin: 10px 0; border-radius: 5px; }
</style></head><body>";

echo "<h1>🔍 Check Routes - Duplicate Finder</h1>";
echo "<hr>";

try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "<h2>📋 Checking Route Definitions...</h2>";
    
    // Get all routes
    $routes = app('router')->getRoutes();
    $routesByName = [];
    $duplicates = [];
    
    foreach ($routes as $route) {
        $name = $route->getName();
        if ($name) {
            if (!isset($routesByName[$name])) {
                $routesByName[$name] = [];
            }
            $routesByName[$name][] = [
                'uri' => $route->uri(),
                'method' => implode('|', $route->methods()),
                'action' => $route->getActionName(),
            ];
        }
    }
    
    // Find duplicates
    foreach ($routesByName as $name => $routes) {
        if (count($routes) > 1) {
            $duplicates[$name] = $routes;
        }
    }
    
    if (empty($duplicates)) {
        echo "<pre class='success'>✅ No duplicate route names found!</pre>";
        echo "<p>All routes have unique names. Route caching should work now.</p>";
    } else {
        echo "<pre class='error'>❌ Found " . count($duplicates) . " duplicate route name(s)!</pre>";
        
        foreach ($duplicates as $name => $routes) {
            echo "<div class='duplicate'>";
            echo "<strong>⚠️  Duplicate: '$name'</strong><br>";
            echo "Found in " . count($routes) . " places:<br>";
            foreach ($routes as $i => $route) {
                echo "<br>" . ($i + 1) . ". <span class='info'>{$route['method']}</span> ";
                echo "<span class='warning'>{$route['uri']}</span><br>";
                echo "   Action: <span class='success'>{$route['action']}</span>";
            }
            echo "</div>";
        }
        
        echo "<h2>🔧 How to Fix:</h2>";
        echo "<pre class='warning'>";
        echo "1. Edit routes/web.php or routes/api.php\n";
        echo "2. Find duplicate route names listed above\n";
        echo "3. Remove or rename one of the duplicates\n";
        echo "4. Clear cache: php artisan route:clear\n";
        echo "5. Try cache again: php artisan route:cache\n";
        echo "</pre>";
    }
    
    echo "<hr>";
    echo "<h2>📊 Route Statistics:</h2>";
    echo "<pre>";
    echo "Total routes: <span class='info'>" . count($routes) . "</span>\n";
    echo "Named routes: <span class='success'>" . count($routesByName) . "</span>\n";
    echo "Duplicates: <span class='" . (empty($duplicates) ? 'success' : 'error') . "'>" . count($duplicates) . "</span>\n";
    echo "</pre>";
    
    echo "<hr>";
    echo "<h2>📝 All Named Routes:</h2>";
    echo "<pre class='info' style='max-height: 400px; overflow-y: auto;'>";
    foreach ($routesByName as $name => $routes) {
        $route = $routes[0];
        $duplicate = count($routes) > 1 ? ' <span class="error">(DUPLICATE!)</span>' : '';
        echo "<span class='success'>{$name}</span> → {$route['method']} /{$route['uri']}{$duplicate}\n";
    }
    echo "</pre>";
    
} catch (\Exception $e) {
    echo "<pre class='error'>❌ Error: " . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<div style='background: #f48771; color: #1e1e1e; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<h2 style='color: #1e1e1e; margin-top: 0;'>⚠️ SECURITY WARNING</h2>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY!</strong></p>";
echo "<p>File: <code>" . __FILE__ . "</code></p>";
echo "</div>";

echo "</body></html>";
?>
