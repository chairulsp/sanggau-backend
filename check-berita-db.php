<?php
/**
 * Simple Check: Berita in Database
 * Direct PDO query to see what's in the berita table
 */

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🔍 Check Berita Database</h2>";
echo "<style>
pre { background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 8px; overflow-x: auto; font-size: 0.9em; }
.success { color: #4ade80; }
.error { color: #f87171; }
.warning { color: #fbbf24; }
table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #1e40af; color: white; }
tr:nth-child(even) { background-color: #f9f9f9; }
.b64 { background-color: #fef3c7; color: #92400e; font-weight: bold; }
</style>";

// Database credentials from .env
$host = 'localhost';
$dbname = 'diskominfo_sanggaudb';
$username = 'diskominfo_sanggau';
$password = 'diskominfo_sanggau26';

echo "<h3>1. Database Connection</h3>";
echo "<pre>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ <span class='success'>Connected to database!</span>\n";
} catch (PDOException $e) {
    echo "❌ <span class='error'>Connection failed:</span> " . $e->getMessage() . "\n";
    exit;
}

echo "</pre>";

// Count total berita
echo "<h3>2. Total Berita in Database</h3>";
echo "<pre>";

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM beritas");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $result['total'];
    
    echo "Total berita: <strong class='success'>$total</strong>\n";
    
    if ($total == 0) {
        echo "\n<span class='warning'>⚠️ No berita found in database!</span>\n";
        echo "This means:\n";
        echo "1. Berita was not saved to database\n";
        echo "2. Or table 'beritas' doesn't exist\n";
        echo "3. Or berita was deleted\n";
    }
} catch (PDOException $e) {
    echo "❌ <span class='error'>Query failed:</span> " . $e->getMessage() . "\n";
}

echo "</pre>";

// Show latest berita
echo "<h3>3. Latest 10 Berita</h3>";

try {
    $stmt = $pdo->query("
        SELECT id, judul, kategori, aktif, penulis, user_id, created_at 
        FROM beritas 
        ORDER BY id DESC 
        LIMIT 10
    ");
    $beritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($beritas) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Penulis</th><th>User ID</th><th>Created At</th></tr>";
        
        foreach ($beritas as $b) {
            $judulClass = (strpos($b['judul'], 'b64:') === 0) ? 'b64' : '';
            $statusIcon = $b['aktif'] ? '✅' : '📝';
            $status = $b['aktif'] ? 'Published' : 'Draft';
            
            echo "<tr>";
            echo "<td>{$b['id']}</td>";
            echo "<td class='$judulClass'>" . htmlspecialchars($b['judul']) . "</td>";
            echo "<td>{$b['kategori']}</td>";
            echo "<td>$statusIcon $status</td>";
            echo "<td>" . htmlspecialchars($b['penulis']) . "</td>";
            echo "<td>{$b['user_id']}</td>";
            echo "<td>{$b['created_at']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Check for b64 encoded data
        $hasB64 = false;
        foreach ($beritas as $b) {
            if (strpos($b['judul'], 'b64:') === 0) {
                $hasB64 = true;
                break;
            }
        }
        
        if ($hasB64) {
            echo "<div style='background: #fef3c7; padding: 1rem; border-radius: 8px; margin: 1rem 0;'>";
            echo "<strong>⚠️ WARNING:</strong> Found berita with <code>b64:</code> prefix (shown in yellow).<br>";
            echo "This means the data was saved without being decoded.<br>";
            echo "<strong>Solution:</strong> Delete these berita and create new ones after uploading the fixed middleware.";
            echo "</div>";
        } else {
            echo "<div style='background: #d1fae5; padding: 1rem; border-radius: 8px; margin: 1rem 0;'>";
            echo "<strong>✅ GOOD:</strong> No base64 encoded data found!";
            echo "</div>";
        }
    } else {
        echo "<p><span class='warning'>⚠️ No berita found</span></p>";
    }
} catch (PDOException $e) {
    echo "<pre><span class='error'>❌ Query failed:</span> " . $e->getMessage() . "</pre>";
}

// Show full details of latest berita
echo "<h3>4. Full Details of Latest Berita</h3>";

try {
    $stmt = $pdo->query("
        SELECT * 
        FROM beritas 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $berita = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($berita) {
        echo "<pre>";
        foreach ($berita as $key => $value) {
            $displayValue = $value;
            
            // Highlight b64 encoded values
            if (is_string($value) && strpos($value, 'b64:') === 0) {
                $displayValue = "<span class='warning'>$value</span>";
                
                // Try to decode
                $decoded = base64_decode(substr($value, 4), true);
                if ($decoded !== false) {
                    $displayValue .= "\n  → Decoded: <span class='success'>" . htmlspecialchars($decoded) . "</span>";
                }
            } else {
                $displayValue = htmlspecialchars($value);
            }
            
            echo str_pad($key, 15) . ": $displayValue\n";
        }
        echo "</pre>";
    } else {
        echo "<p><span class='warning'>⚠️ No berita found</span></p>";
    }
} catch (PDOException $e) {
    echo "<pre><span class='error'>❌ Query failed:</span> " . $e->getMessage() . "</pre>";
}

echo "<hr>";
echo "<h3>💡 Next Steps:</h3>";
echo "<ol>";
echo "<li>If you see <strong class='b64'>b64: prefixed data</strong>: Upload fixed middleware files and delete these test berita</li>";
echo "<li>If no berita found: Try creating a new berita from CMS</li>";
echo "<li>If berita looks good: The fix is working! ✅</li>";
echo "</ol>";
