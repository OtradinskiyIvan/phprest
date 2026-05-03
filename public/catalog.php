<!DOCTYPE html>
<html>
<head>
<title>Каталог</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h1>Каталог товаров</h1>

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

<div class="product-card" data-category="electronics">
    <h3>Смартфон Samsung</h3>
    <p class="product-price">29 990 руб.</p>
    <p>Современный смартфон с отличной камерой.</p>
    <div class="product-category-badge">Электроника</div>
    <a href="products.php#phone">Подробнее</a>
</div>

<div class="product-card" data-category="electronics">
    <h3>Ноутбук Lenovo</h3>
    <p class="product-price">54 990 руб.</p>
    <p>Лёгкий ноутбук для работы и учёбы.</p>
    <div class="product-category-badge">Электроника</div>
    <a href="products.php#laptop">Подробнее</a>
</div>

<div class="product-card" data-category="electronics">
    <h3>Наушники Sony</h3>
    <p class="product-price">8 990 руб.</p>
    <p>Беспроводные наушники с шумоподавлением.</p>
    <div class="product-category-badge">Электроника</div>
    <a href="products.php#headphones">Подробнее</a>
</div>

<div class="product-card" data-category="clothing">
    <h3>Толстовка с логотипом Linux</h3>
    <p class="product-price">3 490 руб.</p>
    <p>Удобная толстовка из качественного хлопка с вышитым логотипом Linux.</p>
    <div class="product-category-badge">Одежда</div>
    <a href="products.php#linux-hoodie">Подробнее</a>
</div>

<div class="product-card" data-category="books">
    <h3>JavaScript для чайников</h3>
    <p class="product-price">1 290 руб.</p>
    <p>Лучшая книга для начинающих программистов. 7-е издание.</p>
    <div class="product-category-badge">Книги</div>
    <a href="products.php#js-book">Подробнее</a>
</div>

<div class="product-card" data-category="books">
    <h3>Базовый минимум по Computer Science</h3>
    <p class="product-price">1 890 руб.</p>
    <p>Ключевые концепции программирования и компьютерных наук.</p>
    <div class="product-category-badge">Книги</div>
    <a href="products.php#cs-book">Подробнее</a>
</div>

<hr>
<div class="footer">
<p>&copy; 2026 Практическая работа №2. Все права защищены.</p>
</div>

</div>

<script src="./script.js"></script>
</body>
</html>