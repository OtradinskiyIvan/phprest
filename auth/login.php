<?php

require_once __DIR__ . '/../include/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Обработка только POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/auth_form.php');
    exit;
}

// Получение данных
$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

// Функция логирования (локальная, чтобы не было конфликтов)
$writeLog = function($login, $action, $extra = '') {
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $line = sprintf(
        "%s | ip=%s | login=%s | action=%s",
        $time,
        $ip,
        htmlspecialchars($login),
        $action
    );
    if ($extra) {
        $line .= ' | ' . $extra;
    }
    $line .= PHP_EOL;
    
    $logFile = __DIR__ . '/logs/auth.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    file_put_contents($logFile, $line, FILE_APPEND);
};

// Валидация
if (empty($login) || empty($password)) {
    $writeLog($login, 'FAIL_LOGIN', 'reason=empty_fields');
    header('Location: /auth_form.php?error=empty_fields');
    exit;
}

// Загрузка динамических пользователей
$usersFile = __DIR__ . '/../data/users.json';
$dynamicUsers = [];
if (file_exists($usersFile)) {
    $json = @file_get_contents($usersFile);
    if ($json !== false) {
        $dynamicUsers = json_decode($json, true) ?? [];
    }
}

// Объединяем пользователей: динамические имеют приоритет
$allUsers = array_merge($users, $dynamicUsers);

// Проверка пользователя
if (isset($allUsers[$login]) && password_verify($password, $allUsers[$login]['password_hash'])) {
    // Успешный вход
    $_SESSION['user_id'] = $allUsers[$login]['id'];
    $_SESSION['login'] = $login;
    
    $writeLog($login, 'SUCCESS_LOGIN');
    
    header('Location: /public/profile.php');
    exit;
}

// Ошибка авторизации
$writeLog($login, 'FAIL_LOGIN', 'reason=invalid');
header('Location: /public/auth_form.php?error=invalid_credentials');
exit;
?>