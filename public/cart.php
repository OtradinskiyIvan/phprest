<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Корзина</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h1>Корзина</h1>

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

<section class="cart-section">
    <h2>Ваши товары</h2>
    <div class="cart-container">
        <div class="cart-items">
            <!-- товарыбудут загружаться из localStorage -->
        </div>
        <div class="cart-total">
            <span>Итого:</span>
            <span id="cart-total">0 руб.</span>
        </div>
        <div class="cart-buttons">
            <button id="clear-cart" class="btn btn-secondary">Очистить корзину</button>
            <button id="checkout" class="btn btn-primary">Оплатить</button>
        </div>
    </div>
</section>

<hr>
<div class="footer">
<p>&copy; 2026 Практическая работа №2. Все права защищены.</p>
</div>

</div>

<script src="./script.js"></script>
</body>
</html>