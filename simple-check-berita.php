<?php
/**
 * SUPER SIMPLE Berita Check
 * Just PDO - No Laravel
 */

$host = 'localhost';
$db = 'diskominfo_sanggaudb';
$user = 'diskominfo_sanggau';
$pass = 'diskominfo_sanggau26';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Berita Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        h2 { color: #1e40af; }
        .box { background: white; padding: 15px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #059669; font-weight: bold; }
        .error { color: #dc2626; font-weight: bold; }
        .warning { color: #d97706; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th { background: #1e40af; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9fafb; }
        .b64-cell { background: #fef3c7 !important; font-weight: bold; color: #92400e; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>

<h2>🔍 Simple Berita Database Check</h2>

<?php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='box'>";
    echo "<strong class='success'>✅ Database Connected!</strong>";
    echo "</div>";
    
    // Count berita
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM beritas");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<div class='box'>";
    echo "<h3>📊 Total Berita: <span class='success'>$count</span></h3>";
    
    if ($count == 0) {
        echo "<p class='warning'>⚠️ Tidak ada berita di database!</p>";
        echo "<p>Coba buat berita baru dari CMS.</p>";
    }
    echo "</div>";
    
    if ($count > 0) {
        // Get latest 10 berita
        $stmt = $pdo->query("
            SELECT id, judul, kategori, aktif, penulis, created_at 
            FROM beritas 
            ORDER BY id DESC 
            LIMIT 10
        ");
        $beritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='box'>";
        echo "<h3>📰 Latest 10 Berita:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Penulis</th><th>Created</th></tr>";
        
        $hasB64 = false;
        foreach ($beritas as $b) {
            $isB64 = (strpos($b['judul'], 'b64:') === 0);
            if ($isB64) $hasB64 = true;
            
            $cellClass = $isB64 ? "class='b64-cell'" : "";
            $status = $b['aktif'] ? '✅ Published' : '📝 Draft';
            
            echo "<tr>";
            echo "<td>{$b['id']}</td>";
            echo "<td $cellClass>" . htmlspecialchars($b['judul']) . "</td>";
            echo "<td>{$b['kategori']}</td>";
            echo "<td>$status</td>";
            echo "<td>" . htmlspecialchars($b['penulis']) . "</td>";
            echo "<td>{$b['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        
        // Show warning if b64 found
        if ($hasB64) {
            echo "<div class='box' style='background: #fef3c7; border-left: 4px solid #d97706;'>";
            echo "<h3 class='warning'>⚠️ PERINGATAN: Data Ter-encode!</h3>";
            echo "<p>Ditemukan berita dengan prefix <code>b64:</code> (ditandai dengan latar kuning).</p>";
            echo "<p><strong>Penyebab:</strong> Middleware DecodeBase64Input tidak aktif saat berita dibuat.</p>";
            echo "<p><strong>Solusi:</strong></p>";
            echo "<ol>";
            echo "<li>Upload file <code>DecodeBase64Input.php</code> dan <code>Kernel.php</code> yang sudah diperbaiki</li>";
            echo "<li>Clear cache: <a href='/clear-cache.php'>clear-cache.php</a></li>";
            echo "<li>Hapus berita dengan prefix b64: dari CMS</li>";
            echo "<li>Buat berita baru - seharusnya sudah normal</li>";
            echo "</ol>";
            echo "</div>";
        } else {
            echo "<div class='box' style='background: #d1fae5; border-left: 4px solid #059669;'>";
            echo "<h3 class='success'>✅ Semua Data Normal!</h3>";
            echo "<p>Tidak ditemukan data ter-encode. Middleware berfungsi dengan baik!</p>";
            echo "</div>";
        }
        
        // Show latest berita full details
        $stmt = $pdo->query("SELECT * FROM beritas ORDER BY id DESC LIMIT 1");
        $latest = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($latest) {
            echo "<div class='box'>";
            echo "<h3>🔎 Detail Berita Terakhir (ID: {$latest['id']}):</h3>";
            echo "<table>";
            echo "<tr><th>Field</th><th>Value</th></tr>";
            
            foreach ($latest as $key => $value) {
                if (is_null($value)) {
                    $display = "<em style='color: #9ca3af;'>null</em>";
                } elseif (is_string($value) && strpos($value, 'b64:') === 0) {
                    // Try decode
                    $decoded = base64_decode(substr($value, 4), true);
                    $display = "<span class='warning'>" . htmlspecialchars($value) . "</span>";
                    if ($decoded !== false) {
                        $display .= "<br><small>→ Decoded: <strong>" . htmlspecialchars($decoded) . "</strong></small>";
                    }
                } elseif (strlen($value) > 200) {
                    $display = htmlspecialchars(substr($value, 0, 200)) . "... <em>(truncated)</em>";
                } else {
                    $display = htmlspecialchars($value);
                }
                
                echo "<tr><td><strong>$key</strong></td><td>$display</td></tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }
    
} catch (PDOException $e) {
    echo "<div class='box' style='background: #fee2e2; border-left: 4px solid #dc2626;'>";
    echo "<h3 class='error'>❌ Database Error</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>

<div class='box' style='background: #eff6ff;'>
    <h3>💡 Quick Actions:</h3>
    <ul>
        <li><a href='/clear-cache.php'>Clear Laravel Cache</a></li>
        <li><a href='https://diskominfo.sanggau.go.id/4dm1n-sgu/berita'>Open CMS Berita</a></li>
        <li><a href='/'>Back to Homepage</a></li>
    </ul>
</div>

</body>
</html>
