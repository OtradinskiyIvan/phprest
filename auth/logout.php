<?php
require_once __DIR__ . '/../include/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$writeLog = function($login, $action) {
    $line = sprintf(
        "%s | ip=%s | login=%s | action=%s\n",
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        htmlspecialchars($login),
        $action
    );
    file_put_contents(LOG_FILE, $line, FILE_APPEND);
};

if (isset($_SESSION['login'])) {
    $writeLog($_SESSION['login'], 'LOGOUT');
}

// Полностью очищаем сессию
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Перенаправляем на главную
header('Location: /public/index.php');
exit;
?>