<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * the static HTML file that was saved from the original WordPress site.
 *
 * @package WordPress
 */

// Serve the static HTML file
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = dirname(__FILE__);

// Remove query string if present
if (false !== $pos = strpos($request_uri, '?')) {
    $request_uri = substr($request_uri, 0, $pos);
}

// Clean up request URI
$request_uri = rtrim($request_uri, '/');

// Default to index.html for root requests
if (empty($request_uri) || $request_uri === '/') {
    include($base_path . '/index.html');
    exit;
}

// Check if the requested file exists
$file_path = $base_path . $request_uri;

// If it's a directory, look for index.html
if (is_dir($file_path)) {
    $file_path = rtrim($file_path, '/') . '/index.html';
    if (file_exists($file_path)) {
        include($file_path);
        exit;
    }
}

// Check for the file with .html extension
if (!file_exists($file_path)) {
    $html_path = $file_path . '.html';
    if (file_exists($html_path)) {
        include($html_path);
        exit;
    }
}

// If the file exists, serve it directly
if (file_exists($file_path)) {
    // Get file extension
    $ext = pathinfo($file_path, PATHINFO_EXTENSION);
    
    // Set appropriate content type
    switch ($ext) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'json':
            header('Content-Type: application/json');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
        case 'pdf':
            header('Content-Type: application/pdf');
            break;
    }
    
    // Output the file contents
    readfile($file_path);
    exit;
}

// If we get here, try to serve the index.html as a fallback
include($base_path . '/index.html');