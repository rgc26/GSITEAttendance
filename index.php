<?php
/**
 * Simple redirect to public directory
 * Updated for root domain deployment
 */

// Get the current request URI
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';

// Since we're at root domain, just redirect to public
$path = $request_uri;

// Build the redirect URL (no base path needed)
$redirect_url = '/public' . $path;

// Add query string if it exists
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
    $redirect_url .= '?' . $_SERVER['QUERY_STRING'];
}

// Perform the redirect
header("Location: {$redirect_url}", true, 301);
exit;
