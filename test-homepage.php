<?php

echo "Testing homepage...\n\n";

$url = 'http://127.0.0.1:8000/';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: " . $httpCode . "\n";

if ($error) {
    echo "CURL Error: " . $error . "\n";
    exit(1);
}

if ($httpCode == 200) {
    echo "✓ SUCCESS - Homepage loaded successfully!\n";
    echo "Response length: " . strlen($response) . " bytes\n";
    
    // Check for key elements
    if (strpos($response, '<title>') !== false) {
        echo "✓ HTML title found\n";
    }
    if (strpos($response, 'Sanggau') !== false) {
        echo "✓ 'Sanggau' text found in page\n";
    }
    
    exit(0);
} else {
    echo "✗ ERROR - HTTP " . $httpCode . "\n";
    echo "Response preview: " . substr($response, 0, 500) . "\n";
    exit(1);
}
