<?php
/**
 * SIMPLE API - Bypass Laravel completely
 * Direct PDO + JSON response
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database connection
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=diskominfo_sanggaudb;charset=utf8mb4',
        'diskominfo_sanggau',
        'diskominfo_sanggau26',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Parse request
$path = $_GET['path'] ?? 'banner';

// Route handling
switch ($path) {
    case 'banner':
        $stmt = $pdo->query("SELECT * FROM banners WHERE aktif = 1 ORDER BY urutan ASC");
        $data = $stmt->fetchAll();
        
        // Format response
        foreach ($data as &$item) {
            if (isset($item['gambar']) && !empty($item['gambar'])) {
                if (!str_starts_with($item['gambar'], 'http')) {
                    $item['gambar'] = 'https://api.diskominfo.sanggau.go.id' . $item['gambar'];
                }
            }
        }
        
        echo json_encode($data);
        break;
        
    case 'berita':
        $stmt = $pdo->query("SELECT * FROM beritas WHERE status = 'published' ORDER BY created_at DESC LIMIT 20");
        $data = $stmt->fetchAll();
        
        foreach ($data as &$item) {
            if (isset($item['gambar']) && !empty($item['gambar'])) {
                if (!str_starts_with($item['gambar'], 'http')) {
                    $item['gambar'] = 'https://api.diskominfo.sanggau.go.id' . $item['gambar'];
                }
            }
        }
        
        echo json_encode($data);
        break;
        
    case 'settings':
        $stmt = $pdo->query("SELECT * FROM settings");
        $rows = $stmt->fetchAll();
        
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        
        echo json_encode($settings);
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
}
?>
