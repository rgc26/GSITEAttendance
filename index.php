<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file serves as the main entry point for Laravel
 * It includes error handling and debugging
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Define the Laravel start time
    define('LARAVEL_START', microtime(true));

    // Change to the project root directory
    chdir(__DIR__);

    // Check if required files exist
    if (!file_exists(__DIR__.'/vendor/autoload.php')) {
        throw new Exception('vendor/autoload.php not found');
    }

    if (!file_exists(__DIR__.'/bootstrap/app.php')) {
        throw new Exception('bootstrap/app.php not found');
    }

    // Include the Laravel bootstrap
    require __DIR__.'/vendor/autoload.php';

    // Bootstrap the Laravel application
    $app = require_once __DIR__.'/bootstrap/app.php';

    // Create the HTTP kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Handle the request
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );

    // Send the response
    $response->send();

    // Terminate the application
    $kernel->terminate($request, $response);

} catch (Exception $e) {
    // Display error information
    echo "<h1>Laravel Error</h1>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    
    // Show server information
    echo "<h2>Server Information</h2>";
    echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
    echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
    echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
    
    // Show file existence check
    echo "<h2>File Check</h2>";
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
    
    // Provide alternative access
    echo "<hr>";
    echo "<p><a href='./public/'>Access via public directory</a></p>";
    echo "<p><a href='./test.php'>Run test file</a></p>";
}
