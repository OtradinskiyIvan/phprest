<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход / Регистрация</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; background: #f5f5f5; }
        .form-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        .form-box h2 { margin: 0 0 20px; text-align: center; }
        
        /* Вкладки */
        .tabs { display: flex; margin-bottom: 20px; border-bottom: 2px solid #eee; }
        .tab-btn { flex: 1; padding: 10px; border: none; background: none; cursor: pointer; font-weight: bold; color: #666; border-bottom: 2px solid transparent; margin-bottom: -2px; }
        .tab-btn.active { color: #007bff; border-bottom-color: #007bff; }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #0056b3; }
        .error { color: #dc3545; margin-bottom: 15px; text-align: center; background: #f8d7da; padding: 10px; border-radius: 4px; }
        .links { margin-top: 15px; text-align: center; font-size: 14px; }
        .links a { color: #007bff; text-decoration: none; }
        .hint { font-size: 12px; color: #666; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Авторизация</h2>
        
        <!-- Сообщение об ошибке -->
        <div id="error" class="error" style="display:none;"></div>
        
        <!-- Вкладки -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('login')">Вход</button>
            <button class="tab-btn" onclick="switchTab('register')">Регистрация</button>
        </div>
        
        <!-- Форма входа -->
        <div id="tab-login" class="tab-content active">
            <form action="../auth/login.php" method="POST">
                <div class="form-group">
                    <label for="login-login">Логин</label>
                    <input type="text" id="login-login" name="login" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="login-password">Пароль</label>
                    <input type="password" id="login-password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn">Войти</button>
            </form>
            <p class="hint">Тестовые аккаунты: admin / admin123</p>
        </div>
        
        <!-- Форма регистрации -->
        <div id="tab-register" class="tab-content">
            <form action="../auth/register.php" method="POST">
                <div class="form-group">
                    <label for="reg-login">Придумайте логин</label>
                    <input type="text" id="reg-login" name="login" required autocomplete="username" minlength="3">
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="reg-password">Придумайте пароль</label>
                    <input type="password" id="reg-password" name="password" required autocomplete="new-password" minlength="6">
                    <p class="hint">Минимум 6 символов</p>
                </div>
                <button type="submit" class="btn" style="background: #28a745;">Зарегистрироваться</button>
            </form>
        </div>
        
        <div class="links">
            <a href="index.php">← На главную</a>
        </div>
    </div>

    <script>
        // Переключение вкладок
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            if (tab === 'login') {
                document.querySelector('.tab-btn:nth-child(1)').classList.add('active');
                document.getElementById('tab-login').classList.add('active');
            } else {
                document.querySelector('.tab-btn:nth-child(2)').classList.add('active');
                document.getElementById('tab-register').classList.add('active');
            }
        }
        
        // Показ ошибки и активация нужной вкладки
        const params = new URLSearchParams(window.location.search);
        const error = params.get('error');
        const tab = params.get('tab');
        const errors = {
            'invalid_credentials': 'Неверный логин или пароль',
            'empty_fields': 'Заполните все поля',
            'auth_required': 'Требуется авторизация',
            'user_exists': 'Пользователь с таким логином уже существует',
            'short_password': 'Пароль должен содержать минимум 6 символов',
            'invalid_email': 'Неверный формат email',
            'email_exists': 'Пользователь с таким email уже существует'
        };
        
        if (error && errors[error]) {
            document.getElementById('error').textContent = errors[error];
            document.getElementById('error').style.display = 'block';
            if (tab === 'register') {
                switchTab('register');
            }
        }
        
        // Авто-открытие вкладки регистрации при необходимости
        if (tab === 'register' && !error) {
            switchTab('register');
        }
    </script>
</body>
</html>