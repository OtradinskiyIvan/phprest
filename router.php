<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$public = __DIR__ . '/public';
$requested = $public . $uri;

if ($uri !== '/' && file_exists($requested)) {
    return false;
}

if (strpos($uri, '/api/v1') === 0) {
    require $public . '/api/index.php';
    return true;
}

if ($uri === '/' || $uri === '/index.php') {
    require $public . '/index.php';
    return true;
}

return false;
