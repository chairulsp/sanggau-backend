<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 public_html Folder Scanner</h2><pre>\n";

$publicHtmlDir = __DIR__;

echo "Scanning: $publicHtmlDir\n\n";

$items = scandir($publicHtmlDir);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    $path = $publicHtmlDir . '/' . $item;
    $isDir = is_dir($path);
    echo "[" . ($isDir ? "DIR" : "FILE") . "] $item\n";
    
    if ($isDir && (strpos($item, 'upload') !== false)) {
        echo "  --- Inside $item: ---\n";
        $subItems = scandir($path);
        foreach ($subItems as $subItem) {
            if ($subItem === '.' || $subItem === '..') continue;
            $subPath = $path . '/' . $subItem;
            $subIsDir = is_dir($subPath);
            echo "    [" . ($subIsDir ? "DIR" : "FILE") . "] $subItem\n";
            
            if ($subIsDir) {
                $files = scandir($subPath);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;
                    echo "      - $file\n";
                }
            }
        }
    }
}

echo "\n</pre>";
