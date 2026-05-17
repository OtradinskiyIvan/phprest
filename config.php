<?php
$users = [
    'admin' => [
        'id' => 1,
        // Хэш пароля 'admin123'
        'password_hash' => '$2y$10$jfcsxhf3bjL1nF0HvlKz..5ia4Ghxg5oo38FE40LqKYRS5REwvwKS',
    ],
    'user' => [
        'id' => 2,
        'password_hash' => '$2y$10$jfcsxhf3bjL1nF0HvlKz..5ia4Ghxg5oo38FE40LqKYRS5REwvwKS',
    ],
];

// Путь к файлу логов
define('LOG_FILE', __DIR__ . '/data/logs/auth.log');

// Создаём папку data/logs, если нет
if (!is_dir(__DIR__ . '/data/logs')) {
    mkdir(__DIR__ . '/data/logs', 0755, true);
}
?>