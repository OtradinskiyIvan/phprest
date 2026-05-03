<!DOCTYPE html>
<html>
<head>
<title>Товар</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

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

<!-- Существующие товары -->
<div id="phone" class="product">
    <h1>Смартфон Samsung</h1>
    <p class="product-price">29 990 руб.</p>

    <h2 class="product-title">Краткое описание товара</h2>
    <p class="short-description">
        Современный смартфон с отличной камерой.
    </p>
    <a href="img/samsung.png" target="_blank">
        <img src="img/samsung.png" alt="Смартфон Samsung" class="product-image">
    </a>

    <h2 class="product-title">Характеристики</h2>
    <ul class="features">
        <li>Экран: 6.5 дюймов, AMOLED</li>
        <li>Процессор: Snapdragon 8 Gen 2</li>
        <li>Память: 128 ГБ</li>
        <li>Камера: 50 МП основная</li>
    </ul>

    <h2 class="product-title">Подробное описание товара</h2>
    <p class="full-description">
        Смартфон Samsung с превосходной камерой и производительностью. Идеален для фотографии, видео и повседневного использования.
    </p>

    <button class="button add-to-cart" data-id="1" data-name="Смартфон Samsung" data-price="29990">Добавить в корзину</button>
</div>

<div id="laptop" class="product">
    <h1>Ноутбук Lenovo</h1>
    <p class="product-price">54 990 руб.</p>

    <h2 class="product-title">Краткое описание товара</h2>
    <p class="short-description">
        Лёгкий ноутбук для работы и учёбы.
    </p>
    <a href="img/lenovo.png" target="_blank">
        <img src="img/lenovo.png" alt="Ноутбук Lenovo" class="product-image">
    </a>

    <h2 class="product-title">Характеристики</h2>
    <ul class="features">
        <li>Экран: 15.6 дюймов, Full HD</li>
        <li>Процессор: Intel Core i5</li>
        <li>Память: 8 ГБ RAM, 512 ГБ SSD</li>
        <li>Вес: 1.8 кг</li>
    </ul>

    <h2 class="product-title">Подробное описание товара</h2>
    <p class="full-description">
        Лёгкий и мощный ноутбук Lenovo для работы и учёбы. Отличная производительность и длительное время работы от батареи.
    </p>

    <button class="button add-to-cart" data-id="2" data-name="Ноутбук Lenovo" data-price="54990">Добавить в корзину</button>
</div>

<div id="headphones" class="product">
    <h1>Наушники Sony</h1>
    <p class="product-price">8 990 руб.</p>

    <h2 class="product-title">Краткое описание товара</h2>
    <p class="short-description">
        Беспроводные наушники с шумоподавлением.
    </p>
    <a href="img/sony.png" target="_blank">
        <img src="img/sony.png" alt="Наушники Sony" class="product-image">
    </a>

    <h2 class="product-title">Характеристики</h2>
    <ul class="features">
        <li>Тип: Беспроводные, накладные</li>
        <li>Шумоподавление: Активное</li>
        <li>Время работы: 30 часов</li>
        <li>Подключение: Bluetooth 5.0</li>
    </ul>

    <h2 class="product-title">Подробное описание товара</h2>
    <p class="full-description">
        Беспроводные наушники Sony с превосходным шумоподавлением. Идеальны для музыки, звонков и путешествий.
    </p>

    <button class="button add-to-cart" data-id="3" data-name="Наушники Sony" data-price="8990">Добавить в корзину</button>
</div>

<div id="linux-hoodie" class="product">
    <h1>Толстовка с логотипом Linux</h1>
    <p class="product-price">3 490 руб.</p>

    <h2 class="product-title">Краткое описание товара</h2>
    <p class="short-description">
        Удобная толстовка из качественного хлопка с вышитым логотипом Linux.
    </p>
    <a href="img/linux-hoodie.png" target="_blank">
        <img src="img/linux-hoodie.png" alt="Толстовка с Linux" class="product-image">
    </a>

    <h2 class="product-title">Характеристики</h2>

    <ul class="features">
        <li>Материал: 100% хлопок</li>
        <li>Размеры: S, M, L, XL</li>
        <li>Цвет: черный</li>
        <li>Вышивка высокого качества</li>
    </ul>

    <h2 class="product-title">Подробное описание товара</h2>

    <p class="full-description">
        Толстовка с символикой Linux - отличный выбор для программиста и системного администратора. 
        Мягкая, удобная, подходит для повседневной носки. Вышивка не выцветает и не стирается после многих стирок.
    </p>

    <button class="button add-to-cart" data-id="4" data-name="Толстовка с логотипом Linux" data-price="3490">Добавить в корзину</button>
</div>

<div id="js-book" class="product">
    <h1>JavaScript для чайников</h1>
    <p class="product-price">1 290 руб.</p>

    <h2 class="product-title">Краткое описание товара</h2>
    <p class="short-description">
        Лучшая книга для начинающих программистов. 7-е издание.
    </p>
    <a href="img/js-book.png" target="_blank">
        <img src="img/js-book.png" alt="Книга JavaScript для чайников" class="product-image">
    </a>

    <h2 class="product-title">Характеристики</h2>

    <ul class="features">
        <li>Автор: Крис Минник</li>
        <li>Издательство: Диалектика</li>
        <li>Год издания: 2024</li>
        <li>Страниц: 320</li>
    </ul>

    <h2 class="product-title">Подробное описание товара</h2>

    <p class="full-description">
        Эта книга позволит вам быстро освоить основы JavaScript и перейти к созданию 
        интерактивных веб-страниц. Простой язык, множество примеров и практических заданий 
        помогут новичкам быстро войти в курс дела.
    </p>

    <button class="button add-to-cart" data-id="5" data-name="JavaScript для чайников" data-price="1290">Добавить в корзину</button>
</div>

<div id="cs-book" class="product">
    <h1>Базовый минимум по Computer Science</h1>
    <p class="product-price">1 890 руб.</p>

    <h2 class="product-title">Краткое описание товара</h2>
    <p class="short-description">
        Ключевые концепции программирования и компьютерных наук.
    </p>
    <a href="img/cs-book.png" target="_blank">
        <img src="img/cs-book.png" alt="Книга по Computer Science" class="product-image">
    </a>

    <h2 class="product-title">Характеристики</h2>

    <ul class="features">
        <li>Автор: Михаил Мозговой</li>
        <li>Издательство: Питер</li>
        <li>Год издания: 2025</li>
        <li>Страниц: 288</li>
    </ul>

    <h2 class="product-title">Подробное описание товара</h2>

    <p class="full-description">
        В этой книге собраны самые важные темы из мира Computer Science: алгоритмы, 
        структуры данных, архитектура компьютеров, основы баз данных и многое другое. 
        Идеально для начинающих программистов и всех, кто хочет систематизировать свои знания.
    </p>

    <button class="button add-to-cart" data-id="6" data-name="Базовый минимум по Computer Science" data-price="1890">Добавить в корзину</button>
</div>

<hr>
<div class="footer">
<p>&copy; 2026 Практическая работа №2. Все права защищены.</p>
</div>

</div>

<script src="./script.js"></script>
</body>
</html>