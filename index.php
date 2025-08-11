<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file serves as the main entry point for Laravel
 * It handles redirects when .htaccess fails
 */

// Get the current request URI
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';

// Extract the path after the domain
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove the base path if it exists
$base_path = '/GSITEAttendance';
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Ensure path starts with /
if (empty($path) || $path[0] !== '/') {
    $path = '/' . $path;
}

// Build the redirect URL
$redirect_url = $base_path . '/public' . $path;

// Add query string if it exists
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
    $redirect_url .= '?' . $_SERVER['QUERY_STRING'];
}

// Set proper headers for redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: {$redirect_url}");
header("Cache-Control: no-cache, must-revalidate");

// Output a fallback message in case headers fail
echo "<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Laravel Application...</title>
    <meta http-equiv='refresh' content='0;url={$redirect_url}'>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .redirect-box { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <div class='redirect-box'>
        <h2>Redirecting...</h2>
        <p>You are being redirected to the Laravel application.</p>
        <p>If you are not redirected automatically, click the button below:</p>
        <a href='{$redirect_url}' class='btn'>Go to Application</a>
    </div>
    <script>
        // JavaScript redirect as backup
        setTimeout(function() {
            window.location.href = '{$redirect_url}';
        }, 1000);
    </script>
</body>
</html>";

exit;
