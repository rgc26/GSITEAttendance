<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file serves as the main entry point for Laravel
 * It bypasses the need for .htaccess redirects
 */

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
