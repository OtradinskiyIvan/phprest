<?php
// config.php

// Пользователи (логин => данные)
$users = [
    'admin' => [
        'id' => 1,
        // Хэш пароля 'admin123' (сгенерирован через password_hash)
        'password_hash' => '$2y$10$jfcsxhf3bjL1nF0HvlKz..5ia4Ghxg5oo38FE40LqKYRS5REwvwKS',
    ],
    'user' => [
        'id' => 2,
        'password_hash' => '$2y$10$jfcsxhf3bjL1nF0HvlKz..5ia4Ghxg5oo38FE40LqKYRS5REwvwKS',
    ],
];

// Путь к файлу логов
define('LOG_FILE', __DIR__ . '/../data/logs/auth.log');

// Создаём папку для логов, если нет
$logDir = dirname(LOG_FILE);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
?>