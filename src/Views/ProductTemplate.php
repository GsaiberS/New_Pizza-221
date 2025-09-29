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
        $title = 'Каталог продукции';

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

        // Генерация меню категорий с использованием русскоязычных названий
        $menuItems = '';
        foreach ($categoryNames as $key => $name) {
            $menuItems .= <<<HTML
            <li class="nav-item">
                <a class="nav-link" href="#$key">$name</a>
            </li>
HTML;
        }

        $menu = <<<HTML
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4" style="position: sticky; top: 0; z-index: 1000;">
            <div class="container-fluid">
                <ul class="navbar-nav justify-content-center w-100">
                    $menuItems
                </ul>
            </div>
        </nav>
HTML;

        // Содержимое страницы
        $content = '';

        // Заголовок страницы с фоновым изображением
        $content .= <<<HTML
        <div class="text-center py-5" style="
            background-image: url('https://i.pinimg.com/736x/74/42/df/7442df5010f9a71af522f38e787e3aea.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            border-radius: 20px; /* Скругление углов */
            overflow: hidden; /* Обрезка фона по границам */
        ">
            <h1 class="display-4 fw-bold">Каталог продукции</h1>
        </div>
HTML;

        // Добавление меню
        $content .= $menu;

        // Генерация разделов для каждой категории
        foreach ($categories as $category => $items) {
            $categoryName = $categoryNames[$category] ?? ucfirst($category); // Русское название или дефолтное

            $content .= <<<HTML
            <section id="$category" class="mb-5">
                <h2 class="text-center mb-4">$categoryName</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
HTML;

            foreach ($items as $item) {
                $content .= <<<HTML
                    <div class="col">
                        <div class="card h-100" style="border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <img src="{$item['image']}" class="card-img-top" alt="{$item['name']}" style="height: 200px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <a href="http://localhost/products/{$item['id']}" style="text-decoration: none; color: inherit;">
                                        <h5 class="card-title">{$item['name']}</h5>
                                    </a>
                                    <p class="card-text">{$item['description']}</p>
                                </div>
                                <div>
                                    <h5 class="card-title"><strong>Цена: </strong>{$item['price']} руб.</h5>
                                    <form class="mt-3 add-to-basket-form" action="/basket" method="POST">
                                        <input type="hidden" name="id" value="{$item['id']}">
                                        <button type="submit" class="btn btn-custom w-100">Добавить в корзину</button>
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

        // ***************************************************************
        // *** ПЛАВАЮЩАЯ КНОПКА КОРЗИНЫ (С ИКОНКОЙ) + АНИМАЦИИ ***
        // ***************************************************************

        // Вычисляем стиль отображения кнопки (логика из ProductController)
        $displayStyle = $basketCount > 0 ? 'flex' : 'none'; 

        $content .= <<<HTML
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
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

            /* --- СТИЛИ КНОПКИ --- */
            .floating-basket-btn {
                position: fixed;
                right: 40px; 
                bottom: 40px; 
                width: 65px;  
                height: 65px; 
                background-color: #d09db0; 
                color: white;
                border-radius: 50%;
                font-size: 26px; 
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
                z-index: 9999;
                transition: background-color 0.3s ease, transform 0.2s ease;
                text-decoration: none;
                display: {$displayStyle}; 
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transform-origin: bottom center; /* Для красивого прыжка */
            }

            .floating-basket-btn:hover {
                background-color: #e6b4c3; 
                transform: scale(1.08); 
            }

            .basket-count {
                position: absolute;
                top: -5px;
                right: -5px;
                background-color: #ff4757; 
                color: white;
                font-size: 12px;
                font-weight: bold;
                border-radius: 50%;
                padding: 3px 6px;
                min-width: 20px;
                height: 20px;
                line-height: 14px;
                text-align: center;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            }

            /* --- СТИЛИ ВСПЛЫВАЮЩЕЙ ПОДСКАЗКИ (ТОСТА) --- */
            .basket-toast {
                position: absolute;
                background-color: #28a745; /* Зеленый цвет успеха */
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                font-size: 12px;
                font-weight: bold;
                opacity: 0;
                top: -30px; /* Над кнопкой */
                left: 50%;
                transform: translateX(-50%);
                pointer-events: none; /* Чтобы не мешать кликам */
                transition: opacity 0.3s ease-out, transform 0.4s ease-out;
                z-index: 10000;
            }
            .basket-toast.show {
                opacity: 1;
                transform: translateX(-50%) translateY(-10px);
            }
        </style>
        
        <a href="/order" class="floating-basket-btn" id="floatingBasketButton">
            <i class="fas fa-shopping-basket"></i>
            <span class="basket-count" id="basketItemCount">{$basketCount}</span>
        </a>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.add-to-basket-form');
                const floatingButton = document.getElementById('floatingBasketButton');
                const basketCountSpan = document.getElementById('basketItemCount');
                
                // Перехват отправки формы
                forms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault(); // Останавливаем стандартную отправку формы
                        
                        const formData = new FormData(this);
                        const button = this.querySelector('button[type="submit"]');

                        // 1. Анимация кнопки и подсказки
                        animateBasket(button, floatingButton);

                        // 2. Асинхронная отправка данных на сервер
                        fetch(this.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            // Ожидаем, что сервер обновит корзину и вернет новый счетчик (или просто обновляем DOM)
                            // Поскольку это простой пример, мы просто имитируем обновление счетчика
                            let currentCount = parseInt(basketCountSpan.textContent) || 0;
                            
                            // В идеале: получить новое количество из ответа сервера
                            // fetch(this.action, ...).then(response => response.json()).then(data => { ... })
                            
                            currentCount += 1; // Имитация: увеличиваем счетчик на 1
                            basketCountSpan.textContent = currentCount;
                            
                            // Показываем кнопку, если она была скрыта
                            floatingButton.style.display = 'flex'; 

                        })
                        .catch(error => {
                            console.error('Ошибка при добавлении в корзину:', error);
                        });
                    });
                });

                function animateBasket(sourceButton, targetButton) {
                    // Анимация 1: Прыжок кнопки корзины
                    targetButton.classList.remove('animate-jump');
                    void targetButton.offsetWidth; // Хак для перезапуска анимации
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
            });
        </script>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
    // ... (метод getCardTemplate остается без изменений) ...
// ...

    /**
     * Генерирует HTML-код для отображения карточки одного товара.
     *
     * @param array $data Данные о товаре (id, name, price, image, description и тт.)
     * @return string HTML-код карточки товара
     */
    public static function getCardTemplate(array $data = null): string
    {
        $template = parent::getTemplate();
        $title = 'Карточка товара';

        // Если данные отсутствуют, показываем сообщение об ошибке
        if ($data === null) {
            $content = '<div class="alert alert-danger" role="alert">Товар не найден.</div>';
            $resultTemplate = sprintf($template, $title, $content);
            return $resultTemplate;
        }

        // Генерация HTML-кода для карточки товара
        $content = <<<HTML
        <div class="card mb-3" style="max-width: 540px; margin: 0 auto;">
            <div class="row g-0">
                <div class="col-md-4 mt-3">
                    <img src="{$data['image']}" class="img-fluid rounded-start" alt="{$data['name']}" style="height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{$data['name']}</h5>
                        <p class="card-text">{$data['description']}</p>
                        <h5 class="card-title"><strong>Цена:</strong> {$data['price']} руб.</h5>
                        <form class="mt-4" action="/basket" method="POST">
                            <input type="hidden" name="id" value="{$data['id']}">
                            <button type="submit" class="btn btn-custom">Добавить в корзину</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}