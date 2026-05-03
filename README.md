# TechStore - Интернет-магазин с REST API

## Описание

Данный проект реализует функционал интернет-магазина с возможностью сохранения корзины покупателя в локальном хранилище браузера (LocalStorage) и REST API для управления пользователями.

Проект разделен на публичную часть (веб-интерфейс) и API для программного доступа.

---

## Структура проекта

```
/
├── public/                 # Публичные файлы
│   ├── index.php          # Главная страница
│   ├── catalog.php        # Каталог товаров
│   ├── cart.php           # Корзина
│   ├── products.php       # Страницы товаров
│   ├── auth_form.php      # Форма авторизации/регистрации
│   ├── profile.php        # Личный кабинет пользователя
│   ├── api/               # REST API
│   │   ├── index.php      # Точка входа API
│   │   └── routes.php     # Маршрутизация API
│   ├── style.css          # Стили
│   ├── script.js          # JavaScript для корзины
│   └── img/               # Изображения
├── include/               # Включаемые файлы
│   ├── config.php         # Конфигурация
│   ├── models/            # Модели данных
│   │   └── User.php       # Модель пользователя
│   └── controllers/       # Контроллеры
│       └── UserController.php # Контроллер пользователей
├── auth/                  # Аутентификация
│   ├── login.php          # Обработка входа
│   ├── register.php       # Обработка регистрации
│   └── logout.php         # Обработка выхода
├── data/                  # Данные
│   ├── users.json         # Пользователи
│   └── logs/              # Логи
├── .htaccess              # Конфигурация Apache
├── router.php             # Маршрутизатор для локального сервера
└── README.md              # Документация
```

---

## Принцип работы

### Веб-интерфейс
- **Главная страница** (`public/index.php`): Приветствие и навигация
- **Каталог** (`public/catalog.php`): Список товаров с ссылками на подробности
- **Корзина** (`public/cart.php`): Управление товарами в корзине с использованием LocalStorage
- **Личный кабинет** (`public/profile.php`): Просмотр и удаление аккаунта
- **Аутентификация**: Формы входа/регистрации (`public/auth_form.php`) с обработкой в `auth/`

### REST API
API предоставляет программный доступ к управлению пользователями. Все запросы возвращают JSON-ответы.

#### Маршрутизация
- Запросы к `/api/v1/*` обрабатываются через `public/api/index.php`
- Маршрутизация по HTTP-методу и пути в `public/api/routes.php`
- Контроллер `include/controllers/UserController.php` обрабатывает бизнес-логику
- Модель `include/models/User.php` работает с данными в JSON-файле

#### Хранение данных
- Пользователи хранятся в `data/users.json`
- Пароли хэшируются с помощью `password_hash()`
- Логи записываются в `data/logs/auth.log`

---

## REST API для пользователей

### Точки доступа

- `POST /api/v1/register` — регистрация пользователя
- `POST /api/v1/login` — авторизация пользователя
- `GET /api/v1/users` — список всех пользователей
- `GET /api/v1/users/{id}` — данные пользователя по ID
- `PUT /api/v1/users/{id}` или `PATCH /api/v1/users/{id}` — обновление пользователя
- `DELETE /api/v1/users/{id}` — удаление пользователя

### Формат запросов и ответов

#### Регистрация (POST /api/v1/register)
```json
// Запрос
{
  "name": "Иван Иванов",
  "email": "ivan@example.com",
  "password": "password123"
}

// Успешный ответ (201)
{
  "status": "success",
  "message": "User registered",
  "user": {
    "id": 3,
    "name": "Иван Иванов",
    "email": "ivan@example.com",
    "registered": "2026-05-01 12:00:00"
  }
}

// Ошибка (400/409)
{
  "status": "error",
  "message": "User with this email already exists"
}
```

#### Авторизация (POST /api/v1/login)
```json
// Запрос
{
  "email": "ivan@example.com",
  "password": "password123"
}

// Успешный ответ (200)
{
  "status": "success",
  "message": "Login successful",
  "user": {
    "id": 3,
    "name": "Иван Иванов",
    "email": "ivan@example.com",
    "registered": "2026-05-01 12:00:00"
  }
}

// Ошибка (401)
{
  "status": "error",
  "message": "Invalid credentials"
}
```

#### Получение списка пользователей (GET /api/v1/users)
```json
// Ответ (200)
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "admin",
      "email": "",
      "registered": "2026-05-01 10:00:00"
    },
    {
      "id": 3,
      "name": "Иван Иванов",
      "email": "ivan@example.com",
      "registered": "2026-05-01 12:00:00"
    }
  ]
}
```

#### Получение пользователя по ID (GET /api/v1/users/3)
```json
// Успешный ответ (200)
{
  "status": "success",
  "data": {
    "id": 3,
    "name": "Иван Иванов",
    "email": "ivan@example.com",
    "registered": "2026-05-01 12:00:00"
  }
}

// Ошибка (404)
{
  "status": "error",
  "message": "User not found"
}
```

#### Обновление пользователя (PUT/PATCH /api/v1/users/3)
```json
// Запрос (обновление пароля)
{
  "password": "newpassword123"
}

// Или обновление email и имени
{
  "email": "newemail@example.com",
  "name": "Иван Петров"
}

// Успешный ответ (200)
{
  "status": "success",
  "message": "User updated",
  "user": {
    "id": 3,
    "name": "Иван Петров",
    "email": "newemail@example.com",
    "registered": "2026-05-01 12:00:00"
  }
}
```

#### Удаление пользователя (DELETE /api/v1/users/3)
```json
// Успешный ответ (200)
{
  "status": "success",
  "message": "User deleted"
}

// Ошибка (404)
{
  "status": "error",
  "message": "User not found"
}
```

---

## Запуск проекта

### Локальный запуск с PHP-сервером
```bash
php -S localhost:8000 router.php
```
Затем откройте http://localhost:8000 в браузере.

### Запуск на Apache/Nginx
Скопируйте проект в корневую директорию веб-сервера. Файл `.htaccess` обеспечит правильную маршрутизацию.

> Если вы используете встроенный PHP-сервер, то в cURL-командах указывайте `localhost:8000`.
> Если проект развернут в Apache/Nginx на порту 80, то `localhost` без порта также будет работать.

---

## Тестирование API с помощью cURL

### 1. Регистрация пользователя
```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Тестовый Пользователь",
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 2. Авторизация пользователя
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "name": "curlauthtest",
    "password": "password123"
  }'
```

Или через email:
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 3. Получение списка пользователей
```bash
curl -X GET http://localhost:8000/api/v1/users
```

### 4. Получение пользователя по ID
```bash
curl -X GET http://localhost:8000/api/v1/users/3
```

### 5. Обновление пароля пользователя
```bash
curl -X PUT http://localhost:8000/api/v1/users/3 \
  -H "Content-Type: application/json" \
  -d '{
    "password": "newpassword123"
  }'
```

### 6. Удаление пользователя
```bash
curl -X DELETE http://localhost:8000/api/v1/users/3
```

---

## Ключевые функции LocalStorage

### Константа ключа хранилища
```javascript
const CART_STORAGE_KEY = 'techstore_cart';
```

### Загрузка корзины из LocalStorage
```javascript
const loadCartFromStorage = () => {
    const savedCart = localStorage.getItem(CART_STORAGE_KEY);
    return savedCart ? JSON.parse(savedCart) : [];
};
```

### Сохранение корзины в LocalStorage
```javascript
const saveCartToStorage = (cart) => {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
};
```

---

## Требования

- PHP 7.4+
- Веб-сервер (Apache/Nginx) или встроенный PHP-сервер
- Поддержка JSON и password_hash()
