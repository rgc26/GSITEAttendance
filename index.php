<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file serves as the main entry point for Laravel
 * It bypasses the need for .htaccess redirects
 */

// Check if this is a direct file access
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    // This is a direct access, redirect to public
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    // Remove the base path if it exists
    $base_path = '/GSITEAttendance';
    if (strpos($path, $base_path) === 0) {
        $path = substr($path, strlen($base_path));
    }
    
    // Build the redirect URL
    $redirect_url = $base_path . '/public' . $path;
    
    // Add query string if it exists
    if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
        $redirect_url .= '?' . $_SERVER['QUERY_STRING'];
    }
    
    header("Location: {$redirect_url}", true, 301);
    exit;
}

// Define the Laravel start time
define('LARAVEL_START', microtime(true));

// Change to the project root directory
chdir(__DIR__);

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
