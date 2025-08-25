<?php
// Advanced WordPress static site launcher

// Configuration
$site_root = __DIR__ . '/marcelhouse.ee';
$port = 8080;
$site_title = 'Marcel House';

// Check if PHP server is available
if (!function_exists('exec')) {
    die("PHP exec function is disabled. Cannot launch server.\n");
}

// Check if site root exists
if (!file_exists($site_root)) {
    die("Site root directory not found at: {$site_root}\n");
}

// Create a router file to handle requests
$router_content = <<<'EOD'
<?php
// Router script for the PHP built-in web server

// Get the requested file path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$document_root = __DIR__;
$requested_file = $document_root . $uri;

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
    
    // Output the file contents
    readfile($requested_file);
    return true;
}

// Check if it's a directory and look for index files
if (is_dir($requested_file)) {
    foreach (['index.php', 'index.html'] as $index_file) {
        if (file_exists($requested_file . '/' . $index_file)) {
            include $requested_file . '/' . $index_file;
            return true;
        }
    }
}

// If no specific file is requested, serve the main index file
if ($uri === '/' || empty($uri)) {
    include $document_root . '/index.html';
    return true;
}

// For WordPress-style URLs, serve the main index file
include $document_root . '/index.html';
return true;
EOD;

// Write the router file
$router_file = $site_root . '/router.php';
file_put_contents($router_file, $router_content);

// Print information
echo "=== WordPress Local Server Launcher ===\n";
echo "Site root: {$site_root}\n";
echo "Server URL: http://localhost:{$port}\n";
echo "Press Ctrl+C to stop the server\n";
echo "======================================\n\n";

// Change to the site root directory
chdir($site_root);

// Launch the PHP built-in server with the router
$command = "php -S localhost:{$port} router.php";
echo "Executing: {$command}\n\n";
system($command);