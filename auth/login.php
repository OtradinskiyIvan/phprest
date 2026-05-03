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

// Нормализуем всех пользователей в единый список
$allUsers = [];
foreach ($users as $userName => $userData) {
    $allUsers[] = [
        'id' => $userData['id'] ?? 0,
        'name' => $userData['name'] ?? $userName,
        'email' => $userData['email'] ?? '',
        'password_hash' => $userData['password_hash'] ?? '',
        'registered' => $userData['registered'] ?? date('Y-m-d H:i:s'),
    ];
}

foreach ($dynamicUsers as $key => $userData) {
    if (!is_array($userData)) {
        continue;
    }

    if (is_string($key) && !isset($userData['name'])) {
        $userData['name'] = $key;
    }

    if (isset($userData['login']) && !isset($userData['name'])) {
        $userData['name'] = $userData['login'];
    }

    $allUsers[] = [
        'id' => $userData['id'] ?? 0,
        'name' => $userData['name'] ?? '',
        'email' => $userData['email'] ?? '',
        'password_hash' => $userData['password_hash'] ?? '',
        'registered' => $userData['registered'] ?? date('Y-m-d H:i:s'),
    ];
}

// Поиск пользователя по имени или email
$foundUser = null;
foreach ($allUsers as $user) {
    if ($user['name'] !== '' && strcasecmp($user['name'], $login) === 0) {
        $foundUser = $user;
        break;
    }
    if ($user['email'] !== '' && strcasecmp($user['email'], $login) === 0) {
        $foundUser = $user;
        break;
    }
}

if ($foundUser && password_verify($password, $foundUser['password_hash'])) {
    // Успешный вход
    $_SESSION['user_id'] = $foundUser['id'];
    $_SESSION['login'] = $foundUser['name'];
    
    $writeLog($foundUser['name'], 'SUCCESS_LOGIN');
    
    header('Location: /public/profile.php');
    exit;
}

// Ошибка авторизации
$writeLog($login, 'FAIL_LOGIN', 'reason=invalid');
header('Location: /public/auth_form.php?error=invalid_credentials');
exit;
?>