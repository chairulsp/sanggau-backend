<?php
/**
 * fix-pegawai-foto.php
 * Upload ke /home/diskominfo/public_html/
 * Akses: https://api.diskominfo.sanggau.go.id/fix-pegawai-foto.php?key=diskominfo2024
 * HAPUS file ini setelah selesai!
 */
if (($_GET['key'] ?? '') !== 'diskominfo2024') {
    http_response_code(403); die('403 Forbidden');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px;line-height:1.6">';
echo "=== FIX FOTO PEGAWAI ===\n\n";

// ── Konfigurasi path ─────────────────────────────────────────────
$publicHtml  = '/home/diskominfo/public_html';   // document root
$storageDir  = '/home/diskominfo/laravel/storage/app/public/pegawai';
$uploadsDir  = $publicHtml . '/uploads/pegawai';

// ── Database (tanpa Laravel bootstrap) ───────────────────────────
$db_host = 'localhost';
$db_name = 'diskominfo_sanggaudb';
$db_user = 'diskominfo_sanggau';
$db_pass = 'diskominfo_sanggau26';

// ── STEP 1: Buat folder uploads/pegawai ──────────────────────────
echo "[ STEP 1 ] Membuat folder uploads/pegawai...\n";
if (!is_dir($uploadsDir)) {
    if (mkdir($uploadsDir, 0755, true)) {
        echo "  ✓ Folder dibuat: $uploadsDir\n";
    } else {
        echo "  ✗ GAGAL membuat folder!\n";
    }
} else {
    echo "  ✓ Folder sudah ada.\n";
}
echo "\n";

// ── STEP 2: Salin foto dari storage ke uploads ───────────────────
echo "[ STEP 2 ] Menyalin foto dari storage ke uploads...\n";
$moved = 0; $skipped = 0; $failed = 0;

if (is_dir($storageDir)) {
    $files = array_diff(scandir($storageDir), ['.', '..']);
    foreach ($files as $file) {
        $src  = $storageDir . '/' . $file;
        $dest = $uploadsDir . '/' . $file;
        if (!is_file($src)) continue;
        if (file_exists($dest)) {
            echo "  SKIP: $file\n"; $skipped++; continue;
        }
        if (copy($src, $dest)) {
            echo "  ✓ COPIED: $file\n"; $moved++;
        } else {
            echo "  ✗ FAILED: $file\n"; $failed++;
        }
    }
    echo "\n  Total — Disalin: $moved | Skip: $skipped | Gagal: $failed\n";
} else {
    echo "  Folder storage tidak ditemukan: $storageDir\n";
}
echo "\n";

// ── STEP 3: Update path di database ─────────────────────────────
echo "[ STEP 3 ] Update path foto di database...\n";
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Cek berapa banyak yang perlu diupdate
    $count = $pdo->query("SELECT COUNT(*) FROM pegawai WHERE foto LIKE '/storage/pegawai/%'")->fetchColumn();
    echo "  Ditemukan $count data dengan path lama (/storage/pegawai/...)\n\n";

    if ($count > 0) {
        $rows = $pdo->query("SELECT id, nama_lengkap, foto FROM pegawai WHERE foto LIKE '/storage/pegawai/%'")->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("UPDATE pegawai SET foto = ? WHERE id = ?");
        foreach ($rows as $row) {
            $newPath = str_replace('/storage/pegawai/', '/uploads/pegawai/', $row['foto']);
            $stmt->execute([$newPath, $row['id']]);
            echo "  ✓ [{$row['id']}] {$row['nama_lengkap']}\n     {$row['foto']}\n     → $newPath\n\n";
        }
        echo "  Total diupdate: $count pegawai.\n";
    } else {
        // Cek apakah ada path /storage/pegawai dengan variasi lain
        $all = $pdo->query("SELECT id, nama_lengkap, foto FROM pegawai WHERE foto IS NOT NULL LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
        echo "  Tidak ada yang perlu diupdate. Sample data saat ini:\n";
        foreach ($all as $row) {
            echo "  [{$row['id']}] {$row['nama_lengkap']}: {$row['foto']}\n";
        }
    }
} catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
echo "\n⚠️  HAPUS file ini sekarang via File Manager cPanel!\n";
echo "Path: /home/diskominfo/public_html/fix-pegawai-foto.php\n";
echo '</pre>';
