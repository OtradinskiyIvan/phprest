const CART_STORAGE_KEY = 'techstore_cart';

const products = [
    { id: 1, name: 'Смартфон Samsung', price: 29990, category: 'electronics' },
    { id: 2, name: 'Ноутбук Lenovo', price: 54990, category: 'electronics' },
    { id: 3, name: 'Наушники Sony', price: 8990, category: 'electronics' },
    
    { id: 4, name: 'Толстовка с логотипом Linux', price: 3490, category: 'clothing' },
    
    { id: 5, name: 'JavaScript для чайников', price: 1290, category: 'books' },
    { id: 6, name: 'Базовый минимум по Computer Science', price: 1890, category: 'books' }
];

const loadCartFromStorage = () => {
    const savedCart = localStorage.getItem(CART_STORAGE_KEY);
    if (!savedCart) return [];

    try {
        const parsed = JSON.parse(savedCart);
        return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        console.warn('Ошибка чтения корзины из localStorage:', error);
        return [];
    }
};

const saveCartToStorage = (cart) => {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
};

let cart = loadCartFromStorage();

const calculateTotal = (cartArray) => {
    return cartArray.reduce((sum, item) => sum + item.price, 0);
};

const isProductInCart = (productId) => {
    return cart.some(item => item.id === productId);
};

const updateButtonStates = () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        const productId = parseInt(button.dataset.id);
        
        if (isProductInCart(productId)) {
            button.style.backgroundColor = 'rgb(76, 175, 80)';
            button.textContent = '✓ В корзине';
            button.disabled = true;
        } else {
            button.style.backgroundColor = '';
            button.textContent = 'Добавить в корзину';
            button.disabled = false;
        }
    });
};

const updateCartDisplay = () => {
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    
    if (!cartItemsContainer) return;

    cartItemsContainer.innerHTML = '';
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p class="empty-cart">Корзина пуста</p>';
        if (cartTotalElement) cartTotalElement.textContent = '0 руб.';
        return;
    }
    
    // Отрисовываем товары в корзине
    cart.forEach((item) => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-info">
                <strong>${item.name}</strong>
                <span>${item.price.toLocaleString()} руб.</span>
            </div>
            <button class="remove-from-cart" data-id="${item.id}">Удалить</button>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
    
    // Добавляем обработчики для кнопок удаления
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', (event) => {
            const productId = parseInt(event.target.dataset.id);
            removeFromCart(productId);
        });
    });
    
    // Обновляем итоговую сумму
    const total = calculateTotal(cart);
    if (cartTotalElement) {
        cartTotalElement.textContent = total.toLocaleString() + ' руб.';
    }
};

// Функция для отображения количества товаров в корзине
const updateCartCounter = () => {
    const cartLink = document.querySelector('a[href="cart.html"]');
    if (cartLink) {
        if (cart.length > 0) {
            cartLink.textContent = `Корзина (${cart.length})`;
        } else {
            cartLink.textContent = 'Корзина';
        }
    }
};

// Функция удаления товара из корзины
const removeFromCart = (productId) => {
    const index = cart.findIndex(item => item.id === productId);
    
    if (index !== -1) {
        cart.splice(index, 1);
        
        saveCartToStorage(cart);
        updateCartDisplay();
        updateButtonStates();
        updateCartCounter();  // Добавляем обновление счётчика
        
        alert('Товар удален из корзины');
    }
};

const clearCart = () => {
    if (cart.length === 0) {
        alert('Корзина и так пуста!');
        return;
    }
    
    if (confirm('Вы уверены, что хотите очистить корзину?')) {
        cart = [];
        
        saveCartToStorage(cart);
        updateCartDisplay();
        updateButtonStates();
        updateCartCounter();  // Добавляем обновление счётчика
    }
};

const checkout = () => {
    if (cart.length === 0) {
        alert('Корзина пуста! Добавьте товары перед оплатой.');
        return;
    }
    
    const total = calculateTotal(cart);
    alert(`Оплата прошла успешно! Сумма покупки: ${total.toLocaleString()} руб. Спасибо за покупку!`);
    
    cart = [];
    saveCartToStorage(cart);
    updateCartDisplay();
    updateButtonStates();
    updateCartCounter();  // Добавляем обновление счётчика
};

const addToCart = (productId, productName, productPrice) => {
    // Проверяем, есть ли уже такой товар в корзине
    if (isProductInCart(productId)) {
        alert('Этот товар уже есть в корзине!');
        return false;
    }
    
    // Добавляем товар в корзину
    cart.push({
        id: productId,
        name: productName,
        price: productPrice
    });
    
    saveCartToStorage(cart);
    
    alert(`${productName} добавлен в корзину!`);
    
    updateButtonStates();
    updateCartCounter();  // Добавляем обновление счётчика
    
    console.log('Текущая корзина:', cart);
    return true;
};

const filterProducts = (category) => {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach((card) => {
        // Получаем категорию товара из data-атрибута
        const productCategory = card.dataset.category;
        
        if (category === 'all' || productCategory === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Показываем сообщение о количестве отфильтрованных товаров
    const visibleCards = Array.from(productCards).filter(card => card.style.display !== 'none');
    const visibleCount = visibleCards.length;
    const filterMessage = document.getElementById('filter-message');
    if (filterMessage) {
        if (category === 'all') {
            filterMessage.textContent = `Показаны все товары (${visibleCount})`;
        } else {
            const categoryName = {
                'electronics': 'Электронике',
                'clothing': 'Одежде',
                'books': 'Книгам'
            }[category] || category;
            filterMessage.textContent = `Найдено товаров в категории "${categoryName}": ${visibleCount}`;
        }
    }
};

// Функция для создания фильтра на странице каталога
const createFilterOnCatalog = () => {
    if (document.querySelector('.filter-section')) return;
    
    const container = document.querySelector('.container');
    const catalogTitle = document.querySelector('h1');
    
    if (catalogTitle && catalogTitle.textContent === 'Каталог товаров') {
        // Создаем секцию фильтра
        const filterSection = document.createElement('div');
        filterSection.className = 'filter-section';
        filterSection.innerHTML = `
            <h3>Фильтр по категориям:</h3>
            <select id="category-filter">
                <option value="all">Все товары</option>
                <option value="electronics">Электроника</option>
                <option value="clothing">Одежда</option>
                <option value="books">Книги</option>
            </select>
            <p id="filter-message" class="filter-message">Показаны все товары (6)</p>
        `;
        
        // Вставляем после заголовка
        catalogTitle.insertAdjacentElement('afterend', filterSection);
        
        // Добавляем обработчик для фильтра
        document.getElementById('category-filter').addEventListener('change', (event) => {
            filterProducts(event.target.value);
        });
    }
};

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    
    // Обработчики для кнопок "Добавить в корзину" на странице products.html
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const productId = parseInt(event.target.dataset.id);
            const productName = event.target.dataset.name;
            const productPrice = parseInt(event.target.dataset.price);
            
            addToCart(productId, productName, productPrice);
        });
    });
    
    updateButtonStates();
    
    // обработчики для страницы корзины
    const clearCartBtn = document.getElementById('clear-cart');
    const checkoutBtn = document.getElementById('checkout');
    
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
    
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', checkout);
    }
    
    updateCartDisplay();
    
    // фильтр на страницу каталога
    if (document.querySelector('.catalog') || window.location.pathname.includes('catalog.html')) {
        createFilterOnCatalog();
    }
    
    updateCartCounter();
});