<?php

if (!isset($argv[1])) {
    echo "Usage: php bin/compile.php <path-to-tabler-icons>\n";
    echo "Example: php bin/compile.php C:\\xampp\\htdocs\\tabler-icons\\icons\n";
    exit(1);
}

$sourceDir = rtrim($argv[1], '/\\');
$destDir = __DIR__ . '/../resources/svg';

if (!is_dir($destDir . '/outline')) {
    mkdir($destDir . '/outline', 0777, true);
}
if (!is_dir($destDir . '/filled')) {
    mkdir($destDir . '/filled', 0777, true);
}

// Function to process and copy SVGs
function processIcons($src, $dst) {
    $files = glob($src . '/*.svg');
    $count = 0;
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Remove comments
        $content = preg_replace('/<!--.*?-->/s', '', $content);
        
        // Remove class="something"
        $content = preg_replace('/ class="[^"]*"/', '', $content);
        
        // Remove empty path
        $content = preg_replace('/<path stroke="none" d="M0 0h24v24H0z" fill="none"\/>/', '', $content);
        
        // Remove empty lines
        $content = preg_replace('/^\h*\v+/m', '', $content);
        
        file_put_contents($dst . '/' . basename($file), trim($content));
        $count++;
    }
    return $count;
}

echo "Processing outline icons...\n";
$outlineCount = processIcons($sourceDir . '/outline', $destDir . '/outline');
echo "Processed $outlineCount outline icons.\n";

echo "Processing filled icons...\n";
$filledCount = processIcons($sourceDir . '/filled', $destDir . '/filled');
echo "Processed $filledCount filled icons.\n";

echo "Done.\n";
