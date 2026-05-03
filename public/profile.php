<?php
require_once __DIR__ . '/../include/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth_form.php?error=auth_required');
    exit;
}

// Обработка удаления аккаунта
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    require_once __DIR__ . '/../include/models/User.php';

    $userId = $_SESSION['user_id'];
    $user = User::findById($userId);

    if ($user) {
        // Логируем удаление
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
        $writeLog($_SESSION['login'], 'ACCOUNT_DELETED');

        // Удаляем пользователя
        User::delete($userId);

        // Очищаем сессию
        $_SESSION = [];
        session_destroy();

        // Перенаправляем на главную
        header('Location: auth_form.php?message=account_deleted');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<div class="header">
<h1>📱 Интернет-магазин TechStore</h1>
<p class="slogan">Качественная электроника по доступным ценам</p>
</div>

<ul class="menu">
<li><a href="index.php">Главная</a></li>
<li><a href="catalog.php">Каталог</a></li>
<li><a href="cart.php">Корзина</a></li>
<li><a href="../auth/logout.php">Выйти</a></li>
</ul>

<div class="welcome">
<h2>Личный кабинет</h2>
<p>Добро пожаловать, <strong><?= htmlspecialchars($_SESSION['login']) ?></strong>!</p>
<p>Ваш ID: <?= $_SESSION['user_id'] ?></p>
</div>

<div class="account-info">
<h3>Информация об аккаунте</h3>
<p><strong>Логин:</strong> <?= htmlspecialchars($_SESSION['login']) ?></p>
<p><strong>Дата регистрации:</strong> <?php
require_once __DIR__ . '/../include/models/User.php';
$user = User::findById($_SESSION['user_id']);
echo $user ? htmlspecialchars($user['registered']) : 'Неизвестно';
?></p>
</div>

<div class="account-actions">
<h3>Действия с аккаунтом</h3>
<form method="POST" onsubmit="return confirm('Вы действительно хотите удалить аккаунт? Это действие нельзя отменить.');">
    <button type="submit" name="delete_account" value="1" class="delete-btn" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
        Удалить аккаунт
    </button>
</form>
<p style="color: #666; font-size: 14px; margin-top: 10px;">⚠️ Удаление аккаунта необратимо и приведёт к потере всех данных.</p>
</div>

<hr>
<div class="footer">
<p>&copy; 2026 Практическая работа №2. Все права защищены.</p>
</div>

</div>

<script src="./script.js"></script>
</body>
</html>