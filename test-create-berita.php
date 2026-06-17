<?php
/**
 * Test Create Berita API
 * Direct test without browser to debug the validation error
 */

// Simulate POST request to create berita
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/api/4dm1n-sgu/berita';
$_SERVER['HTTP_HOST'] = 'api.diskominfo.sanggau.go.id';
$_SERVER['HTTPS'] = 'on';

// Test data
$testData = [
    'judul' => 'Test Berita ' . time(),
    'konten' => '<p>Ini adalah konten test berita</p>',
    'ringkasan' => 'Ini ringkasan test',
    'kategori' => 'Pemerintahan',
    'penulis' => 'Test User',
    'status' => 'published',
    'tags' => 'test,berita',
    'published_at' => date('Y-m-d H:i:s'),
];

echo "<h2>🧪 Test Create Berita</h2>";
echo "<pre>";

echo "Test Data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

echo str_repeat("=", 60) . "\n\n";

// Check if validation rules would pass
echo "Validation Check:\n";
echo str_repeat("-", 60) . "\n";

$rules = [
    'judul' => 'required|string|max:255',
    'konten' => 'nullable|string',
    'ringkasan' => 'nullable|string',
    'kategori' => 'nullable|string|max:100',
    'penulis' => 'nullable|string|max:255',
    'status' => 'nullable|string|in:draft,published',
    'tags' => 'nullable|string|max:255',
    'published_at' => 'nullable|date',
];

foreach ($rules as $field => $rule) {
    $value = $testData[$field] ?? null;
    $rulesParts = explode('|', $rule);
    
    $errors = [];
    
    foreach ($rulesParts as $r) {
        if ($r === 'required' && empty($value)) {
            $errors[] = "Required but empty";
        } elseif (strpos($r, 'max:') === 0) {
            $max = (int)substr($r, 4);
            if (strlen($value) > $max) {
                $errors[] = "Too long (max $max)";
            }
        } elseif (strpos($r, 'in:') === 0) {
            $allowed = explode(',', substr($r, 3));
            if (!in_array($value, $allowed)) {
                $errors[] = "Not in allowed values: " . implode(', ', $allowed) . " (got: '$value')";
            }
        } elseif ($r === 'date') {
            if ($value && strtotime($value) === false) {
                $errors[] = "Invalid date format";
            }
        } elseif ($r === 'string') {
            if ($value !== null && !is_string($value)) {
                $errors[] = "Not a string (type: " . gettype($value) . ")";
            }
        }
    }
    
    if (empty($errors)) {
        echo "✅ $field: OK\n";
    } else {
        echo "❌ $field: " . implode(', ', $errors) . "\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// Check specifically the status field
echo "Status Field Details:\n";
echo str_repeat("-", 60) . "\n";
$status = $testData['status'];
echo "Value: '$status'\n";
echo "Type: " . gettype($status) . "\n";
echo "Length: " . strlen($status) . "\n";
echo "Hex: " . bin2hex($status) . "\n";
echo "In allowed values (draft,published): " . (in_array($status, ['draft', 'published']) ? 'YES ✅' : 'NO ❌') . "\n";

echo "\n" . str_repeat("=", 60) . "\n";

echo "\n📝 Summary:\n";
echo "If all validations show ✅, then the issue is NOT with validation rules.\n";
echo "The problem might be:\n";
echo "1. Middleware is modifying the request data\n";
echo "2. Authentication/Authorization issue\n";
echo "3. Database connection issue\n";
echo "\nNext step: Check storage/logs/laravel.log for the actual request data\n";

echo "</pre>";
