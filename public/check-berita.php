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
    echo "✓ Koneksi database berhasil\n\n";

    // Tampilkan semua tabel yang ada
    echo "=== TABEL YANG ADA DI DATABASE ===\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $t) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
        echo "  $t ($count rows)\n";
    }

    // Jalankan migration berita jika tabel tidak ada
    if (isset($_GET['migrate']) && $_GET['migrate'] === '1') {
        echo "\n=== MEMBUAT TABEL berita ===\n";
        $sql = "CREATE TABLE IF NOT EXISTS `berita` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `judul` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `ringkasan` text DEFAULT NULL,
            `konten` longtext DEFAULT NULL,
            `gambar` varchar(255) DEFAULT NULL,
            `penulis` varchar(255) DEFAULT NULL,
            `kategori` varchar(100) DEFAULT NULL,
            `tags` varchar(255) DEFAULT NULL,
            `featured` tinyint(1) NOT NULL DEFAULT 0,
            `aktif` tinyint(1) NOT NULL DEFAULT 0,
            `views` int(11) NOT NULL DEFAULT 0,
            `published_at` timestamp NULL DEFAULT NULL,
            `user_id` bigint(20) UNSIGNED DEFAULT NULL,
            `editor_id` bigint(20) UNSIGNED DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `berita_slug_unique` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $pdo->exec($sql);
        echo "✓ Tabel berita berhasil dibuat!\n";

        // Cek apakah ada data berita lama di tabel lain
        echo "\n=== CEK DATA BERITA LAMA ===\n";
        $altTables = ['news', 'artikel', 'posts', 'berita_old'];
        foreach ($altTables as $alt) {
            try {
                $c = $pdo->query("SELECT COUNT(*) FROM `$alt`")->fetchColumn();
                echo "  Ditemukan tabel '$alt' dengan $c rows\n";
            } catch(Exception $e) {
                // tabel tidak ada, skip
            }
        }
    } else {
        echo "\nUntuk membuat tabel berita:\n";
        echo "https://api.diskominfo.sanggau.go.id/check-berita.php?key=diskominfo2024&migrate=1\n";
    }

} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

echo '</pre>';
