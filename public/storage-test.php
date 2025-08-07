<?php
// Simple storage URL test
echo "<h1>Storage URL Test</h1>";

// Test the storage URL generation
$testFile = 'profile_pictures/1754541258_Screenshot 2025-07-31 015112.png';
$storageUrl = 'http://localhost/webSys/storage/' . $testFile;

echo "<h2>Test File: $testFile</h2>";
echo "<p><strong>Storage URL:</strong> $storageUrl</p>";

// Check if file exists
$filePath = '../storage/app/public/' . $testFile;
echo "<p><strong>File exists:</strong> " . (file_exists($filePath) ? 'YES' : 'NO') . "</p>";

// Try to display the image
echo "<h2>Image Test:</h2>";
echo "<img src='$storageUrl' alt='Test Image' style='max-width: 200px; border: 1px solid #ccc;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
echo "<div style='display:none; color: red;'>Image failed to load</div>";

echo "<h2>Available Files:</h2>";
$profilePath = '../storage/app/public/profile_pictures';
if (is_dir($profilePath)) {
    $files = scandir($profilePath);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $url = 'http://localhost/webSys/storage/profile_pictures/' . $file;
            echo "<li><a href='$url' target='_blank'>$file</a></li>";
        }
    }
    echo "</ul>";
}
?>
