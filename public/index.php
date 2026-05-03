<!DOCTYPE html>
<html>
<head>
<title>Магазин электроники</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<div class="header">
<h1>📱 Интернет-магазин TechStore</h1>
<p class="slogan">Качественная электроника по доступным ценам</p>
</div>

<ul class="menu">
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user_id'])) {
    echo '<li><a href="profile.php">Профиль</a></li>';
    echo '<li><a href="../auth/logout.php">Выйти</a></li>';
} else {
    echo '<li><a href="auth_form.php" style="color: #007bff; text-decoration: none;">🔐 Войти</a></li>';
}
?>
<li><a href="index.php">Главная</a></li>
<li><a href="catalog.php">Каталог</a></li>
<li><a href="cart.php">Корзина</a></li>
</ul>

<div class="welcome">
<p>Добро пожаловать в наш магазин!</p>
</div>

<div class="about">
<h3>О нас</h3>
<p>Работаем с 2015 года. Только оригинальная техника, гарантия качества и быстрая доставка.</p>
</div>

<div class="contacts">
<h3>Контакты</h3>
<p>г. Москва, ул. Электронная, д. 1</p>
<p>Телефон: 8 (800) 123-45-67</p>
<p>Email: info@techstore.ru</p>
<p>Ежедневно с 10:00 до 21:00</p>
</div>

<hr>
<div class="footer">
<p>&copy; 2026 Практическая работа №2. Все права защищены.</p>
</div>

</div>

<script src="./script.js"></script>
</body>
</html>