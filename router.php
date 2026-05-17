<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$public = __DIR__ . '/public';
$requested = $public . $uri;

if (strpos($uri, '/api/v1') === 0) {
    require $public . '/api/index.php';
    return true;
}

if ($uri === '/' || $uri === '/index.php') {
    require $public . '/index.php';
    return true;
}

if (is_file($requested)) {
    $ext = strtolower(pathinfo($requested, PATHINFO_EXTENSION));
    if ($ext === 'php') {
        require $requested;
        return true;
    }

    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'html' => 'text/html; charset=UTF-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'json' => 'application/json',
        'txt' => 'text/plain',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'eot' => 'application/vnd.ms-fontobject',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
    ];
    $mime = $mimeTypes[$ext] ?? mime_content_type($requested) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($requested));
    readfile($requested);
    return true;
}

return false;
