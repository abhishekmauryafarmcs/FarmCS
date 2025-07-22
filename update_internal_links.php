<?php
function updateInternalLinks($directory) {
    $updatedFiles = [];
    
    // Recursive function to find PHP and HTML files
    $iterator = new RecursiveDirectoryIterator($directory);
    $recursiveIterator = new RecursiveIteratorIterator($iterator);
    
    foreach ($recursiveIterator as $file) {
        // Only process PHP and HTML files
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'html', 'htm'])) {
            $filePath = $file->getPathname();
            $content = file_get_contents($filePath);
            
            // Patterns to replace
            $patterns = [
                // Replace .html links
                '/(href=["\']\/)?([\w-]+)\.html(["\']\s*>|\s*["\']\s*>)/i',
                
                // Replace relative links
                '/(src=["\']\/)?([\w-]+)\.html(["\']\s*>|\s*["\']\s*>)/i',
                
                // Replace links in JavaScript
                '/([\'"])([\/\w-]+)\.html([\'"])/i'
            ];
            
            $replacements = [
                '$1$2.php$3',
                '$1$2.php$3',
                '$1$2.php$3'
            ];
            
            $newContent = preg_replace($patterns, $replacements, $content);
            
            // Only write if content changed
            if ($newContent !== $content) {
                file_put_contents($filePath, $newContent);
                $updatedFiles[] = $filePath;
            }
        }
    }
    
    return $updatedFiles;
}

// Run the link update
$directory = 'c:/xampp/htdocs/FarmCS';
$updatedFiles = updateInternalLinks($directory);

// Logging
$logFile = $directory . '/link_update_log.txt';
$logContent = "Internal Link Update Log - " . date('Y-m-d H:i:s') . "\n\n";
$logContent .= "Updated Files:\n";
foreach ($updatedFiles as $file) {
    $logContent .= "$file\n";
}

file_put_contents($logFile, $logContent);

// Output results
echo "<pre>";
echo "Link Update Complete!\n\n";
echo "Updated Files:\n";
print_r($updatedFiles);
echo "</pre>";
?>
