<?php
// Configuration
$sourceDir = 'c:/xampp/htdocs/FarmCS/';
$htmlFiles = [];

// Function to recursively find HTML files
function findHtmlFiles($dir) {
    $files = [];
    $iterator = new RecursiveDirectoryIterator($dir);
    $recursiveIterator = new RecursiveIteratorIterator($iterator);
    
    foreach ($recursiveIterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'html') {
            $files[] = $file->getPathname();
        }
    }
    
    return $files;
}

// Find HTML files
$htmlFiles = findHtmlFiles($sourceDir);

// Conversion function
function convertHtmlToPHP($htmlFile) {
    $phpFile = str_replace('.html', '.php', $htmlFile);
    
    // Read HTML file content
    $content = file_get_contents($htmlFile);
    
    // Write to PHP file
    file_put_contents($phpFile, $content);
    
    return [
        'html' => $htmlFile,
        'php' => $phpFile
    ];
}

// Convert files
$convertedFiles = [];
foreach ($htmlFiles as $htmlFile) {
    $converted = convertHtmlToPHP($htmlFile);
    $convertedFiles[] = $converted;
}

// Remove HTML files
$removedFiles = [];
foreach ($htmlFiles as $htmlFile) {
    unlink($htmlFile);
    $removedFiles[] = $htmlFile;
}

// Logging
$logFile = $sourceDir . 'conversion_log.txt';
$logContent = "HTML to PHP Conversion Log - " . date('Y-m-d H:i:s') . "\n\n";
$logContent .= "Converted Files:\n";
foreach ($convertedFiles as $file) {
    $logContent .= "HTML: {$file['html']} -> PHP: {$file['php']}\n";
}
$logContent .= "\nRemoved HTML Files:\n";
foreach ($removedFiles as $file) {
    $logContent .= "$file\n";
}

file_put_contents($logFile, $logContent);

// Output results
echo "<pre>";
echo "Conversion Complete!\n\n";
echo "Converted Files:\n";
print_r($convertedFiles);
echo "\nRemoved HTML Files:\n";
print_r($removedFiles);
echo "</pre>";
?>
