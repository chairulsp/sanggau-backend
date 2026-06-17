<?php
/**
 * check-foto.php — cek apa yang ada di uploads dan database
 * Akses: https://api.diskominfo.sanggau.go.id/check-foto.php?key=diskominfo2024
 * HAPUS setelah selesai!
 */
if (($_GET['key'] ?? '') !== 'diskominfo2024') { http_response_code(403); die('403'); }

$db_host = 'localhost';
$db_name = 'diskominfo_sanggaudb';
$db_user = 'diskominfo_sanggau';
$db_pass = 'diskominfo_sanggau26';

echo '<pre style="font-family:monospace;font-size:12px;background:#111;color:#0f0;padding:20px">';

// Cek file di uploads/pegawai
$uploadsDir = '/home/diskominfo/public_html/uploads/pegawai';
echo "=== FILES DI uploads/pegawai ===\n";
if (is_dir($uploadsDir)) {
    $files = array_diff(scandir($uploadsDir), ['.','..']);
    echo "Total file: " . count($files) . "\n";
    foreach (array_slice($files, 0, 5) as $f) {
        $url = 'https://api.diskominfo.sanggau.go.id/uploads/pegawai/' . $f;
        echo "  $f\n  → $url\n";
    }
} else {
    echo "Folder tidak ada!\n";
}

echo "\n=== DATABASE (5 sample) ===\n";
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $rows = $pdo->query("SELECT id, nama_lengkap, foto FROM pegawai WHERE foto IS NOT NULL LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $fullUrl = 'https://api.diskominfo.sanggau.go.id' . $r['foto'];
        $fileExists = file_exists('/home/diskominfo/public_html' . $r['foto']) ? 'FILE ADA ✓' : 'FILE TIDAK ADA ✗';
        echo "[{$r['id']}] {$r['nama_lengkap']}\n";
        echo "  DB path : {$r['foto']}\n";
        echo "  Full URL : $fullUrl\n";
        echo "  Status   : $fileExists\n\n";
    }
} catch(Exception $e) { echo "DB Error: " . $e->getMessage(); }

echo '</pre>';
