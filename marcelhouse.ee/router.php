<?php
// Router script for the PHP built-in web server

// Get the requested file path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$document_root = __DIR__;
$requested_file = $document_root . $uri;

// Set the base URL for the local server
$base_url = 'http://localhost:8080';
$original_domain = 'https://marcelhouse.ee';

// Handle language switching
$languages = ['en', 'fi', 'ru'];
$current_lang = '';

// Check if the URI starts with a language code
foreach ($languages as $lang) {
    if (preg_match('#^/' . $lang . '(/|$)#', $uri)) {
        $current_lang = $lang;
        break;
    }
}

// Handle file requests directly
if (is_file($requested_file)) {
    // Get file extension
    $ext = pathinfo($requested_file, PATHINFO_EXTENSION);
    
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
        case 'woff':
        case 'woff2':
            header('Content-Type: font/' . $ext);
            break;
        case 'ttf':
            header('Content-Type: font/ttf');
            break;
        case 'eot':
            header('Content-Type: application/vnd.ms-fontobject');
            break;
    }
    
    // For HTML files, replace URLs before serving
    if ($ext == 'html') {
        $content = file_get_contents($requested_file);
        // Replace all instances of the original domain with local server URL
        $content = str_replace($original_domain, $base_url, $content);
        // Also replace escaped URLs in JavaScript
        $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
        echo $content;
    } else {
        // Output the file contents directly for non-HTML files
        readfile($requested_file);
    }
    return true;
}

// Check if it's a directory and look for index files
if (is_dir($requested_file)) {
    foreach (['index.php', 'index.html'] as $index_file) {
        if (file_exists($requested_file . '/' . $index_file)) {
            if ($index_file == 'index.html') {
                $content = file_get_contents($requested_file . '/' . $index_file);
                // Replace all instances of the original domain with local server URL
                $content = str_replace($original_domain, $base_url, $content);
                // Also replace escaped URLs in JavaScript
                $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
                echo $content;
            } else {
                include $requested_file . '/' . $index_file;
            }
            return true;
        }
    }
}

// If no specific file is requested, serve the main index file
if ($uri === '/' || empty($uri)) {
    $content = file_get_contents($document_root . '/index.html');
    // Replace all instances of the original domain with local server URL
    $content = str_replace($original_domain, $base_url, $content);
    // Also replace escaped URLs in JavaScript
    $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
    echo $content;
    return true;
}

// Handle language-specific directories
if (!empty($current_lang)) {
    // Check if it's a directory and look for index files in language directory
    $lang_path = str_replace('/' . $current_lang, '', $requested_file);
    if (is_dir($lang_path)) {
        foreach (['index.php', 'index.html'] as $index_file) {
            if (file_exists($lang_path . '/' . $index_file)) {
                if ($index_file == 'index.html') {
                    $content = file_get_contents($lang_path . '/' . $index_file);
                    // Replace all instances of the original domain with local server URL
                    $content = str_replace($original_domain, $base_url, $content);
                    // Also replace escaped URLs in JavaScript
                    $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
                    echo $content;
                } else {
                    include $lang_path . '/' . $index_file;
                }
                return true;
            }
        }
    }
    
    // Try to find the file without the language prefix
    $lang_file = str_replace('/' . $current_lang, '', $requested_file);
    if (file_exists($lang_file)) {
        $ext = pathinfo($lang_file, PATHINFO_EXTENSION);
        if ($ext == 'html') {
            $content = file_get_contents($lang_file);
            // Replace all instances of the original domain with local server URL
            $content = str_replace($original_domain, $base_url, $content);
            // Also replace escaped URLs in JavaScript
            $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
            echo $content;
        } else {
            include $lang_file;
        }
        return true;
    }
    
    // If language-specific file doesn't exist, try the language index
    if (file_exists($document_root . '/' . $current_lang . '/index.html')) {
        $content = file_get_contents($document_root . '/' . $current_lang . '/index.html');
        // Replace all instances of the original domain with local server URL
        $content = str_replace($original_domain, $base_url, $content);
        // Also replace escaped URLs in JavaScript
        $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
        echo $content;
        return true;
    }
}

// For WordPress-style URLs, serve the appropriate language index file
if (!empty($current_lang)) {
    if (file_exists($document_root . '/' . $current_lang . '/index.html')) {
        $content = file_get_contents($document_root . '/' . $current_lang . '/index.html');
        // Replace all instances of the original domain with local server URL
        $content = str_replace($original_domain, $base_url, $content);
        // Also replace escaped URLs in JavaScript
        $content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);
        echo $content;
        return true;
    }
}

// Default fallback to main index file
$content = file_get_contents($document_root . '/index.html');

// Replace all absolute URLs with local server URLs
$content = str_replace($original_domain, $base_url, $content);
// Also replace escaped URLs in JavaScript
$content = str_replace(str_replace('/', '\/', $original_domain), str_replace('/', '\/', $base_url), $content);

// Output the modified content
echo $content;
return true;