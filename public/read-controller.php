<?php
/**
 * read-controller.php
 * Upload ke /home/diskominfo/public_html/
 * Akses: https://api.diskominfo.sanggau.go.id/read-controller.php?key=diskominfo2024
 * HAPUS setelah selesai!
 */
if (($_GET['key'] ?? '') !== 'diskominfo2024') { http_response_code(403); die('403'); }

$file = __DIR__ . '/app/Http/Controllers/Api/PublicController.php';

echo '<pre style="font-family:monospace;font-size:11px;background:#111;color:#0f0;padding:20px;white-space:pre-wrap">';
echo "File: $file\n";
echo "Exists: " . (file_exists($file) ? 'YES' : 'NO') . "\n";
echo "Size: " . (file_exists($file) ? filesize($file) . ' bytes' : '0') . "\n\n";
echo "=== ISI FILE ===\n\n";
if (file_exists($file)) {
    echo htmlspecialchars(file_get_contents($file));
}
echo '</pre>';
