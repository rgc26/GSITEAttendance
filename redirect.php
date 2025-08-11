<?php
/**
 * Simple redirect file to avoid method conflicts
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

// Perform the redirect
header("Location: {$redirect_url}", true, 301);
exit;
