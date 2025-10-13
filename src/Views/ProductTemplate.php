<?php
namespace App\Views;
use App\Views\BaseTemplate;

class ProductTemplate extends BaseTemplate
{
    /**
     * @param array $arr Массив всех продуктов
     * @param int $basketCount Текущее количество товаров в корзине
     * @return string
     */
    public static function getAllTemplate(array $arr, int $basketCount): string
    {
        $template = parent::getTemplate();
        $title = 'Каталог продукции - Bubble Pizza';

        // Разделение продуктов по категориям
        $categories = [
            'pizza' => [],
            'snack' => [],
            'drink' => [],
            'sauce' => [],
        ];

        // Массив соответствия ключей категорий и их русскоязычных названий
        $categoryNames = [
            'pizza' => 'Пицца',
            'snack' => 'Закуски',
            'drink' => 'Напитки',
            'sauce' => 'Соусы',
        ];

        foreach ($arr as $item) {
            if (isset($categories[$item['category']])) {
                $categories[$item['category']][] = $item;
            }
        }

        // Определяем стиль отображения кнопки корзины
        $basketDisplayStyle = $basketCount > 0 ? 'flex' : 'none';

        // Генерация меню категорий с использованием русскоязычных названий
        $menuItems = '';
        foreach ($categoryNames as $key => $name) {
            $menuItems .= <<<HTML
            <li class="nav-item">
                <a class="nav-link category-nav-link" href="#$key">$name</a>
            </li>
HTML;
        }

        $menu = <<<HTML
        <nav class="category-navbar">
            <div class="container-fluid">
                <ul class="nav justify-content-center w-100">
                    $menuItems
                </ul>
            </div>
        </nav>
HTML;

        // Содержимое страницы
        $content = '';

        // Заголовок страницы в стиле "О нас"
        $content .= <<<HTML
        <div class="catalog-hero text-center position-relative">
            <div class="floating-pizza pizza-1">🍕</div>
            <div class="floating-pizza pizza-2">🍕</div>
            <div class="floating-pizza pizza-3">🍕</div>
            <div class="floating-pizza pizza-4">🍕</div>
            
            <h1 class="catalog-title">
                Каталог 
                <img src="/assets/image/BP.ico" alt="Bubble Pizza" class="title-icon">
                Bubble Pizza
            </h1>
            <p class="lead fs-4 text-dark opacity-75 mb-3">Выберите свою идеальную пиццу и не только!</p>
        </div>
HTML;

        // Добавление меню
        $content .= $menu;

        // Генерация разделов для каждой категории
        foreach ($categories as $category => $items) {
            if (empty($items)) continue;
            
            $categoryName = $categoryNames[$category] ?? ucfirst($category);

            $content .= <<<HTML
            <section id="$category" class="category-section mb-5">
                <h2 class="category-title text-center mb-4">$categoryName</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
HTML;

            foreach ($items as $item) {
                $content .= <<<HTML
                    <div class="col">
                        <div class="card h-100 product-card">
                            <div class="product-image-container">
                                <img src="{$item['image']}" class="card-img-top product-image" alt="{$item['name']}">
                                <div class="product-overlay">
                                    <a href="http://localhost/products/{$item['id']}" class="view-details-btn">
                                        <i class="fas fa-eye"></i>
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <a href="http://localhost/products/{$item['id']}" class="product-link">
                                        <h5 class="card-title product-name">{$item['name']}</h5>
                                    </a>
                                    <p class="card-text product-description">{$item['description']}</p>
                                </div>
                                <div>
                                    <h5 class="card-title product-price"><strong>Цена: </strong>{$item['price']} руб.</h5>
                                    <form class="mt-3 add-to-basket-form" action="/basket" method="POST">
                                        <input type="hidden" name="id" value="{$item['id']}">
                                        <button type="submit" class="btn btn-custom w-100 add-to-cart-btn">
                                            <i class="fas fa-plus"></i>
                                            Добавить в корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
HTML;
            }

            $content .= <<<HTML
                </div>
            </section>
HTML;
        }

        // Стили для каталога
        $content .= <<<HTML
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            /* Анимации */
            @keyframes float {
                0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); }
                25% { transform: translateY(-15px) translateX(8px) rotate(3deg); }
                50% { transform: translateY(8px) translateX(-12px) rotate(-2deg); }
                75% { transform: translateY(-10px) translateX(-8px) rotate(2deg); }
            }

            @keyframes slideUp {
                to { opacity: 1; transform: translateY(0); }
            }

            /* Герой-секция каталога */
            .catalog-hero {
                background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 50%, rgba(102,126,234,0.1) 100%);
                border-radius: 25px;
                padding: 4rem 2rem;
                margin-bottom: 3rem;
                position: relative;
                overflow: hidden;
                text-align: center;
            }
            
            /* Плавающие пиццы */
            .floating-pizza {
                position: absolute;
                font-size: 3rem;
                opacity: 0.15;
                animation: float 8s ease-in-out infinite;
                z-index: 1;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                pointer-events: none;
            }
            
            .pizza-1 { top: 15%; left: 8%; animation-delay: 0s; }
            .pizza-2 { top: 25%; right: 10%; animation-delay: 2s; }
            .pizza-3 { bottom: 20%; left: 12%; animation-delay: 4s; }
            .pizza-4 { bottom: 30%; right: 8%; animation-delay: 6s; }

            .catalog-title {
                font-size: 3rem;
                font-weight: 800;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
                position: relative;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1rem;
            }
            
            .title-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                object-fit: contain;
            }

            /* Навигация по категориям */
            .category-navbar {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                padding: 1rem;
                margin-bottom: 3rem;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                position: sticky;
                top: 20px;
                z-index: 100;
            }
            
            .category-nav-link {
                color: #667eea;
                font-weight: 600;
                padding: 0.5rem 1.5rem;
                border-radius: 25px;
                transition: all 0.3s ease;
                margin: 0 0.5rem;
                text-decoration: none;
            }
            
            .category-nav-link:hover,
            .category-nav-link.active {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white !important;
                transform: translateY(-2px);
            }

            /* Секции категорий */
            .category-section {
                opacity: 0;
                transform: translateY(30px);
                animation: slideUp 0.8s ease forwards;
            }
            
            .category-title {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 2rem;
            }

            /* Карточки продуктов */
            .product-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: all 0.4s ease;
                overflow: hidden;
            }
            
            .product-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 40px rgba(102,126,234, 0.2);
            }

            .product-image-container {
                position: relative;
                height: 200px;
                overflow: hidden;
            }
            
            .product-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }
            
            .product-card:hover .product-image {
                transform: scale(1.1);
            }
            
            .product-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(102,126,234, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .product-card:hover .product-overlay {
                opacity: 1;
            }
            
            .view-details-btn {
                color: white;
                text-decoration: none;
                padding: 12px 24px;
                border: 2px solid white;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .view-details-btn:hover {
                background: white;
                color: #667eea;
            }

            .product-link {
                text-decoration: none;
                color: inherit;
            }
            
            .product-name {
                color: #333;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }
            
            .product-description {
                color: #666;
                line-height: 1.5;
            }
            
            .product-price {
                color: #667eea;
                font-weight: 700;
            }

            .btn-custom {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                border: none;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 20px;
            }
            
            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102,126,234, 0.4);
                color: white;
            }

            /* --- АНИМАЦИИ КОРЗИНЫ --- */
            @keyframes basket-jump {
                0% { transform: scale(1) rotate(0deg); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); }
                25% { transform: scale(1.15) rotate(5deg); box-shadow: 0 8px 20px rgba(255, 71, 87, 0.7); }
                50% { transform: scale(1.15) rotate(-5deg); }
                100% { transform: scale(1) rotate(0deg); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); }
            }
            
            .floating-basket-btn.animate-jump {
                animation: basket-jump 0.5s ease-in-out;
            }

            /* --- СТИЛИ КНОПКИ КОРЗИНЫ --- */
            .floating-basket-btn {
                position: fixed;
                right: 55px;
                bottom: 80px;
                width: 70px;  
                height: 70px; 
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                border-radius: 50%;
                font-size: 24px; 
                box-shadow: 0 8px 25px rgba(102,126,234, 0.4);
                z-index: 99999;
                transition: all 0.15s ease; /* Очень быстрая анимация */
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border: 3px solid white;
                transform: translateY(0);
                opacity: 1;
            }

            /* Классы для анимации скролла */
            .floating-basket-btn.scroll-hide {
                transform: translateY(15px); /* Маленькое смещение */
                opacity: 0.8; /* Легкая прозрачность */
            }

            .floating-basket-btn.scroll-show {
                transform: translateY(0);
                opacity: 1;
            }

            .floating-basket-btn:hover {
                transform: scale(1.1);
                box-shadow: 0 12px 35px rgba(102,126,234, 0.6);
            }

            .basket-count {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #ff4757;
                color: white;
                font-size: 12px;
                font-weight: bold;
                border-radius: 50%;
                padding: 4px 8px;
                min-width: 22px;
                height: 22px;
                line-height: 14px;
                text-align: center;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                border: 2px solid white;
            }

            /* --- СТИЛИ ВСПЛЫВАЮЩЕЙ ПОДСКАЗКИ (ТОСТА) --- */
            .basket-toast {
                position: absolute;
                background: #28a745;
                color: white;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: bold;
                opacity: 0;
                top: -40px;
                left: 50%;
                transform: translateX(-50%);
                pointer-events: none;
                transition: opacity 0.3s ease-out, transform 0.4s ease-out;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
            
            .basket-toast.show {
                opacity: 1;
                transform: translateX(-50%) translateY(-5px);
            }

            /* Адаптивность */
            @media (max-width: 768px) {
                .catalog-title {
                    font-size: 2.2rem;
                    flex-direction: column;
                    gap: 0.5rem;
                }
                
                .title-icon {
                    width: 40px;
                    height: 40px;
                }
                
                .category-title {
                    font-size: 2rem;
                }
                
                .floating-pizza {
                    font-size: 2rem;
                }
                
                .pizza-1 { top: 10%; left: 5%; }
                .pizza-2 { top: 15%; right: 5%; }
                .pizza-3 { bottom: 15%; left: 8%; }
                .pizza-4 { bottom: 20%; right: 5%; }
                
                .floating-basket-btn {
                    right: 20px;
                    bottom: 20px;
                    width: 60px;
                    height: 60px;
                    font-size: 20px;
                }
                
                .floating-basket-btn.scroll-hide {
                    transform: translateY(10px);
                    opacity: 0.8;
                }
                
                .category-navbar .nav {
                    flex-wrap: wrap;
                }
                
                .category-nav-link {
                    margin: 0.25rem;
                    padding: 0.5rem 1rem;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.add-to-basket-form');
                const floatingButton = document.getElementById('floatingBasketButton');
                const basketCountSpan = document.getElementById('basketItemCount');
                const categoryLinks = document.querySelectorAll('.category-nav-link');
                
                // Переменные для управления скроллом
                let lastScrollTop = 0;
                let scrollTimeout = null;
                
                // Функция для обработки скролла
                function handleScroll() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollDirection = scrollTop > lastScrollTop ? 'down' : 'up';
                    
                    // Мгновенная реакция на скролл
                    if (scrollDirection === 'down' && scrollTop > 50) {
                        // Скролл вниз - слегка смещаем кнопку
                        floatingButton.classList.remove('scroll-show');
                        floatingButton.classList.add('scroll-hide');
                    } else {
                        // Скролл вверх или вверху страницы - сразу показываем
                        floatingButton.classList.remove('scroll-hide');
                        floatingButton.classList.add('scroll-show');
                    }
                    
                    lastScrollTop = scrollTop;
                    
                    // Очищаем предыдущий таймаут
                    if (scrollTimeout) {
                        clearTimeout(scrollTimeout);
                    }
                    
                    // Автоматически показываем кнопку через 0.5 секунды после остановки скролла
                    scrollTimeout = setTimeout(() => {
                        floatingButton.classList.remove('scroll-hide');
                        floatingButton.classList.add('scroll-show');
                    }, 500); // Очень быстро - 0.5 секунды
                }
                
                // Обработчик скролла с троттлингом
                let scrollThrottle = null;
                window.addEventListener('scroll', function() {
                    if (!scrollThrottle) {
                        scrollThrottle = setTimeout(() => {
                            handleScroll();
                            scrollThrottle = null;
                        }, 25); // Минимальная задержка
                    }
                });
                
                // Плавный скролл к категориям с отступом
                categoryLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            const offsetTop = targetElement.offsetTop - 100;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                            
                            // Подсветка активной категории
                            categoryLinks.forEach(l => l.classList.remove('active'));
                            this.classList.add('active');
                            
                            // Показываем кнопку после скролла
                            setTimeout(() => {
                                floatingButton.classList.remove('scroll-hide');
                                floatingButton.classList.add('scroll-show');
                            }, 300);
                        }
                    });
                });

                // Анимация появления секций
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.animationPlayState = 'running';
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('.category-section').forEach(el => {
                    observer.observe(el);
                });

                // Перехват отправки формы
                forms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        const formData = new FormData(this);
                        const button = this.querySelector('button[type="submit"]');

                        // 1. Анимация кнопки и подсказки
                        animateBasket(button, floatingButton);

                        // 2. Асинхронная отправка данных на сервер
                        fetch(this.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Получаем актуальное количество из ответа сервера
                            const newCount = data.basketCount || data.count;
                            if (newCount !== undefined) {
                                basketCountSpan.textContent = newCount;
                                floatingButton.style.display = 'flex';
                                // Показываем кнопку при добавлении товара
                                floatingButton.classList.remove('scroll-hide');
                                floatingButton.classList.add('scroll-show');
                            } else {
                                // Если сервер не вернул количество, увеличиваем на 1
                                let currentCount = parseInt(basketCountSpan.textContent) || 0;
                                currentCount += 1;
                                basketCountSpan.textContent = currentCount;
                                floatingButton.style.display = 'flex';
                                floatingButton.classList.remove('scroll-hide');
                                floatingButton.classList.add('scroll-show');
                            }
                        })
                        .catch(error => {
                            console.error('Ошибка при добавлении в корзину:', error);
                            // В случае ошибки все равно обновляем интерфейс
                            let currentCount = parseInt(basketCountSpan.textContent) || 0;
                            currentCount += 1;
                            basketCountSpan.textContent = currentCount;
                            floatingButton.style.display = 'flex';
                            floatingButton.classList.remove('scroll-hide');
                            floatingButton.classList.add('scroll-show');
                        });
                    });
                });

                function animateBasket(sourceButton, targetButton) {
                    // Анимация 1: Прыжок кнопки корзины
                    targetButton.classList.remove('animate-jump');
                    void targetButton.offsetWidth;
                    targetButton.classList.add('animate-jump');
                    
                    // Анимация 2: Всплывающая подсказка "Добавлено"
                    let toast = sourceButton.parentNode.querySelector('.basket-toast');
                    if (!toast) {
                        toast = document.createElement('div');
                        toast.className = 'basket-toast';
                        toast.textContent = 'Добавлено!';
                        sourceButton.parentNode.insertBefore(toast, sourceButton);
                    }
                    
                    // Показываем тост
                    toast.classList.add('show');
                    
                    // Скрываем тост через 1.5 секунды
                    setTimeout(() => {
                        toast.classList.remove('show');
                    }, 1500);
                }
                
                // Инициализация - показываем кнопку при загрузке
                setTimeout(() => {
                    floatingButton.classList.add('scroll-show');
                }, 300);
            });
        </script>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        
        // Добавляем кнопку корзины ПОСЛЕ всего контента
        $resultTemplate .= <<<HTML
        <!-- Плавающая кнопка корзины -->
        <a href="/order" class="floating-basket-btn" id="floatingBasketButton" style="display: $basketDisplayStyle">
            <i class="fas fa-shopping-basket"></i>
            <span class="basket-count" id="basketItemCount">$basketCount</span>
        </a>
HTML;

        return $resultTemplate;
    }

    /**
     * Генерирует HTML-код для отображения карточки одного товара.
     */
    public static function getCardTemplate(array $data = null): string
    {
        $template = parent::getTemplate();
        $title = 'Карточка товара - ' . ($data['name'] ?? '');

        if ($data === null) {
            $content = '<div class="alert alert-danger" role="alert">Товар не найден.</div>';
            $resultTemplate = sprintf($template, $title, $content);
            return $resultTemplate;
        }

        $content = <<<HTML
        <div class="container mt-4">
            <div class="product-detail-card">
                <div class="product-detail-image">
                    <img src="{$data['image']}" alt="{$data['name']}">
                </div>
                <div class="product-detail-content">
                    <h1 class="product-detail-name">{$data['name']}</h1>
                    <p class="product-detail-description">{$data['description']}</p>
                    <div class="product-detail-price">{$data['price']} ₽</div>
                    <form class="product-detail-form" action="/basket" method="POST">
                        <input type="hidden" name="id" value="{$data['id']}">
                        <button type="submit" class="btn btn-custom large">
                            <i class="fas fa-plus"></i>
                            Добавить в корзину
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <style>
            .product-detail-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                padding: 2rem;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                align-items: center;
                max-width: 1000px;
                margin: 0 auto;
            }
            
            .product-detail-image img {
                width: 100%;
                height: 400px;
                object-fit: cover;
                border-radius: 15px;
            }
            
            .product-detail-name {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
            }
            
            .product-detail-description {
                color: #666;
                font-size: 1.1rem;
                line-height: 1.6;
                margin-bottom: 2rem;
            }
            
            .product-detail-price {
                font-size: 2rem;
                font-weight: 700;
                color: #667eea;
                margin-bottom: 2rem;
            }
            
            .btn-custom.large {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                border: none;
                padding: 15px 30px;
                border-radius: 25px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .btn-custom.large:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(102,126,234, 0.4);
            }
            
            @media (max-width: 768px) {
                .product-detail-card {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                    text-align: center;
                }
            }
        </style>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}