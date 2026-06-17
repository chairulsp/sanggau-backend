<?php
/**
 * check-berita.php
 * Upload ke /home/diskominfo/public_html/
 * Akses: https://api.diskominfo.sanggau.go.id/check-berita.php?key=diskominfo2024
 * HAPUS setelah selesai!
 */
if (($_GET['key'] ?? '') !== 'diskominfo2024') { http_response_code(403); die('403'); }

$db_host = 'localhost';
$db_name = 'diskominfo_sanggaudb';
$db_user = 'diskominfo_sanggau';
$db_pass = 'diskominfo_sanggau26';

echo '<pre style="font-family:monospace;font-size:12px;background:#111;color:#0f0;padding:20px">';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);

    $total   = $pdo->query("SELECT COUNT(*) FROM berita")->fetchColumn();
    $aktif   = $pdo->query("SELECT COUNT(*) FROM berita WHERE aktif = 1")->fetchColumn();
    $nonaktif = $pdo->query("SELECT COUNT(*) FROM berita WHERE aktif = 0 OR aktif IS NULL")->fetchColumn();

    echo "Total berita : $total\n";
    echo "Aktif (1)    : $aktif\n";
    echo "Draft/Non (0): $nonaktif\n\n";

    echo "=== 5 BERITA TERBARU (semua status) ===\n";
    $rows = $pdo->query("SELECT id, judul, aktif, published_at FROM berita ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo "[{$r['id']}] aktif={$r['aktif']} | published_at={$r['published_at']} | {$r['judul']}\n";
    }

    // Fix: aktifkan semua berita yang published_at tidak null
    if (isset($_GET['fix']) && $_GET['fix'] === '1') {
        echo "\n=== FIX: Aktifkan semua berita yang published_at tidak null ===\n";
        $updated = $pdo->exec("UPDATE berita SET aktif = 1 WHERE published_at IS NOT NULL AND aktif = 0");
        echo "Diupdate: $updated berita\n";

        // Juga aktifkan yang published_at null tapi ada kontennya
        $updated2 = $pdo->exec("UPDATE berita SET aktif = 1, published_at = created_at WHERE published_at IS NULL AND aktif = 0 AND judul != ''");
        echo "Diupdate (tanpa published_at): $updated2 berita\n";
    } else {
        echo "\nTambahkan ?fix=1 ke URL untuk mengaktifkan semua berita.\n";
        echo "URL: https://api.diskominfo.sanggau.go.id/check-berita.php?key=diskominfo2024&fix=1\n";
    }

} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

echo '</pre>';
