<?php

require_once __DIR__ . '/../include/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/auth_form.php');
    exit;
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$email = trim($_POST['email'] ?? '');
$usersFile = __DIR__ . '/../data/users.json';

// Логирование
$writeLog = function($login, $action, $extra = '') {
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $line = sprintf(
        "%s | ip=%s | login=%s | action=%s",
        $time, $ip, htmlspecialchars($login), $action
    );
    if ($extra) $line .= ' | ' . $extra;
    $line .= PHP_EOL;
    @file_put_contents(LOG_FILE, $line, FILE_APPEND);
};

// Валидация
if (empty($login) || empty($password)) {
    
// logout.php$writeLog($login, 'FAIL_REGISTER', 'reason=empty_fields');
    header('Location: /public/auth_form.php?error=empty_fields&tab=register');
    exit;
}

if (strlen($password) < 6) {
    $writeLog($login, 'FAIL_REGISTER', 'reason=short_password');
    header('Location: /public/auth_form.php?error=short_password&tab=register');
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $writeLog($login, 'FAIL_REGISTER', 'reason=invalid_email');
    header('Location: /public/auth_form.php?error=invalid_email&tab=register');
    exit;
}

// Загрузка динамических пользователей
$dynamicUsers = [];
if (file_exists($usersFile)) {
    $json = @file_get_contents($usersFile);
    if ($json !== false) {
        $dynamicUsers = json_decode($json, true) ?? [];
    }
}

// Приводим динамические данные к списку пользователей
$normalizedDynamicUsers = [];
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
    $normalizedDynamicUsers[] = [
        'id' => $userData['id'] ?? 0,
        'name' => $userData['name'] ?? '',
        'email' => $userData['email'] ?? '',
        'password_hash' => $userData['password_hash'] ?? '',
        'registered' => $userData['registered'] ?? date('Y-m-d H:i:s'),
    ];
}

// Проверка существования
$userExists = false;
$emailExists = false;
foreach ($normalizedDynamicUsers as $userData) {
    if (strcasecmp($userData['name'], $login) === 0 || ($userData['email'] !== '' && strcasecmp($userData['email'], $login) === 0)) {
        $userExists = true;
        break;
    }
    if (strcasecmp($userData['email'], $email) === 0) {
        $emailExists = true;
    }
}

foreach ($users as $userName => $userData) {
    if (strcasecmp($userName, $login) === 0 || ($userData['email'] ?? '' !== '' && strcasecmp($userData['email'] ?? '', $login) === 0)) {
        $userExists = true;
    }
    if (($userData['email'] ?? '') !== '' && strcasecmp($userData['email'] ?? '', $email) === 0) {
        $emailExists = true;
    }
}

if ($userExists) {
    $writeLog($login, 'FAIL_REGISTER', 'reason=user_exists');
    header('Location: /public/auth_form.php?error=user_exists&tab=register');
    exit;
}

if ($emailExists) {
    $writeLog($login, 'FAIL_REGISTER', 'reason=email_exists');
    header('Location: /public/auth_form.php?error=email_exists&tab=register');
    exit;
}

// Создание нового пользователя
$newId = count($users) + count($normalizedDynamicUsers) + 1;
$newHash = password_hash($password, PASSWORD_DEFAULT);

$normalizedDynamicUsers[] = [
    'id' => $newId,
    'name' => $login,
    'email' => $email,
    'password_hash' => $newHash,
    'registered' => date('Y-m-d H:i:s'),
];

// Сохранение в файл
if (@file_put_contents($usersFile, json_encode($normalizedDynamicUsers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
    $writeLog($login, 'FAIL_REGISTER', 'reason=file_save');
    header('Location: /public/auth_form.php?error=server_error&tab=register');
    exit;
}

// Лог и вход
$writeLog($login, 'SUCCESS_REGISTER');
$_SESSION['user_id'] = $newId;
$_SESSION['login'] = $login;

http_response_code(302);
header('Location: /public/profile.php');
exit;
