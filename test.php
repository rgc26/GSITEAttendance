<?php
echo "<h1>PHP Test Page</h1>";
echo "<p>PHP is working!</p>";
echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<p>Server software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p>Document root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
echo "<p>Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "</p>";
echo "<p>Script name: " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "</p>";

// Check if Laravel files exist
echo "<h2>File Check:</h2>";
$files = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'app/Http/Controllers/TeacherController.php',
    'routes/web.php'
];

foreach ($files as $file) {
    $exists = file_exists($file) ? 'EXISTS' : 'MISSING';
    echo "<p>{$file}: {$exists}</p>";
}

echo "<hr>";
echo "<p><a href='./public/'>Go to public directory</a></p>";
?>
