<?php

namespace App\Views;

use App\Views\BaseTemplate;

class OrderTemplate extends BaseTemplate
{
    /**
     * @param array $arr
     * @param array $userData
     * @return string
     */
    public static function getOrderTemplate(array $arr, array $userData = []): string
    {
        $template = parent::getTemplate();
        $title = 'Оформление заказа - Bubble Pizza';
        $username = htmlspecialchars($userData['username'] ?? '');
        $email = htmlspecialchars($userData['email'] ?? '');
        $address = htmlspecialchars($userData['address'] ?? '');
        $phone = htmlspecialchars($userData['phone'] ?? '');

        $content = <<<HTML
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        .order-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 2rem;
        }
        
        .order-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .order-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d09db0, #667eea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .order-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .cart-item {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(102,126,234,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.15);
        }
        
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .quantity-controls {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 50px;
            padding: 0.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quantity-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover {
            transform: scale(1.1);
        }
        
        .btn-decrease {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }
        
        .btn-increase {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
        }
        
        .quantity-display {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
            color: #333;
        }
        
        .total-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .payment-option {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid transparent;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            text-align: left;
            width: 100%;
            cursor: pointer;
        }
        
        .payment-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.2);
        }
        
        .payment-option.active {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
        }
        
        .payment-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(102,126,234,0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
        }
        
        .btn-order {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width: 100%;
            cursor: pointer;
        }
        
        .btn-order:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-order:disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .btn-order::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-order:hover:not(:disabled)::before {
            left: 100%;
        }
        
        .btn-order:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
        
        .btn-clear {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(108, 117, 125, 0.2);
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-clear:hover {
            border-color: #dc3545;
            color: #dc3545;
            transform: translateY(-1px);
        }
        
        .empty-cart {
            text-align: center;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .empty-cart i {
            font-size: 4rem;
            background: linear-gradient(135deg, #d09db0, #667eea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        
        .sticky-sidebar {
            position: sticky;
            top: 2rem;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102,126,234,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title {
            background: linear-gradient(135deg, #d09db0, #667eea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pizza-decoration {
            position: absolute;
            font-size: 1.5rem;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .pizza-1 { top: 10%; left: 5%; animation-delay: 0s; }
        .pizza-2 { top: 60%; right: 10%; animation-delay: 2s; }
        .pizza-3 { bottom: 20%; left: 15%; animation-delay: 4s; }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .form-full-width {
            grid-column: 1 / -1;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        </style>

        <div class="order-container position-relative">
            <div class="pizza-decoration pizza-1">🍕</div>
            <div class="pizza-decoration pizza-2">🍕</div>
            <div class="pizza-decoration pizza-3">🍕</div>
            
            <div class="order-header fade-in-up">
                <h1>Оформление заказа</h1>
                <p>Завершите ваш заказ вкуснейшей пиццы</p>
            </div>
            
            <form action="/order" method="POST" id="order-form">
                <div class="row">
                    <!-- Левая колонка - Корзина и данные -->
                    <div class="col-lg-8">
                        <!-- Корзина -->
                        <div class="info-card fade-in-up">
                            <h3 class="section-title">
                                <i class="fas fa-shopping-cart"></i>Ваша корзина
                            </h3>
HTML;

        $all_sum = 0;

        if (!empty($arr)) {
            foreach ($arr as $product) {
                $name = htmlspecialchars($product['name']);
                $price = htmlspecialchars($product['price']);
                $quantity = htmlspecialchars($product['quantity']);

                $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/assets/image/{$product['id']}.png";
                $image = file_exists($imagePath)
                    ? "/assets/image/{$product['id']}.png"
                    : '/assets/image/default.png';

                $sum = $price * $quantity;
                $all_sum += $sum;

                $decreaseAction = ($quantity > 1) ? '/basket_decrease' : '/basket_remove';
                $decreaseIcon = ($quantity > 1) ? 'fa-minus' : 'fa-trash-alt';
                $decreaseClass = ($quantity > 1) ? 'btn-decrease' : 'btn-decrease';

                $content .= <<<LINE
                <div class="cart-item fade-in-up">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{$image}" class="product-image" alt="{$name}">
                        </div>
                        <div class="col">
                            <h5 class="mb-1 fw-bold">{$name}</h5>
                            <p class="text-muted mb-2">{$price} ₽ за шт.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-success">{$sum} ₽</span>
                                <div class="quantity-controls">
                                    <form action="{$decreaseAction}" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="{$product['id']}">
                                        <button type="submit" class="quantity-btn {$decreaseClass}">
                                            <i class="fas {$decreaseIcon}"></i>
                                        </button>
                                    </form>
                                    <span class="quantity-display">{$quantity}</span>
                                    <form action="/basket_increase" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="{$product['id']}">
                                        <button type="submit" class="quantity-btn btn-increase">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
LINE;
            }

            $content .= <<<HTML
                        <div class="total-card fade-in-up">
                            <h3 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Итого: {$all_sum} ₽
                            </h3>
                        </div>
HTML;
        } else {
            $content .= <<<HTML
                        <div class="empty-cart fade-in-up">
                            <i class="fas fa-shopping-cart"></i>
                            <h4>Корзина пуста</h4>
                            <p class="text-muted">Добавьте пиццу в корзину, чтобы оформить заказ</p>
                            <a href="/products" class="btn-order mt-3">
                                <i class="fas fa-pizza-slice me-2"></i>Перейти в каталог
                            </a>
                        </div>
HTML;
        }

        $content .= <<<HTML
                        </div>
                        
                        <!-- Данные для доставки -->
                        <div class="info-card fade-in-up">
                            <h3 class="section-title">
                                <i class="fas fa-truck"></i>Данные для доставки
                            </h3>
                            <div class="form-grid">
                                <div class="form-full-width">
                                    <label class="form-label fw-semibold">ФИО:</label>
                                    <input type="text" class="form-control" value="{$username}" name="fio" placeholder="Введите ваше ФИО" required>
                                </div>
                                <div>
                                    <label class="form-label fw-semibold">Email:</label>
                                    <input type="email" class="form-control" value="{$email}" name="email" placeholder="Введите ваш email" required>
                                </div>
                                <div>
                                    <label class="form-label fw-semibold">Телефон:</label>
                                    <input type="tel" class="form-control" value="{$phone}" name="phone" placeholder="Введите номер телефона" required>
                                </div>
                                <div class="form-full-width">
                                    <label class="form-label fw-semibold">Адрес доставки:</label>
                                    <input type="text" class="form-control" value="{$address}" name="address" placeholder="Введите адрес доставки" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Правая колонка - Оплата и подтверждение -->
                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="sticky-sidebar">
                            <!-- Способ оплаты -->
                            <div class="info-card fade-in-up">
                                <h3 class="section-title text-center">
                                    <i class="fas fa-credit-card"></i>Способ оплаты
                                </h3>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-light payment-option" data-payment="sbp">
                                        <i class="fas fa-qrcode payment-icon"></i>
                                        <div>
                                            <strong>СБП</strong>
                                            <br><small class="text-muted">Быстрый перевод</small>
                                        </div>
                                    </button>
                                    <button type="button" class="btn btn-light payment-option" data-payment="card">
                                        <i class="fas fa-credit-card payment-icon"></i>
                                        <div>
                                            <strong>Банковская карта</strong>
                                            <br><small class="text-muted">Онлайн оплата</small>
                                        </div>
                                    </button>
                                    <button type="button" class="btn btn-light payment-option" data-payment="yandex_pay">
                                        <i class="fab fa-yandex payment-icon"></i>
                                        <div>
                                            <strong>Yandex Pay</strong>
                                            <br><small class="text-muted">Быстрая оплата</small>
                                        </div>
                                    </button>
                                    <button type="button" class="btn btn-light payment-option" data-payment="cash">
                                        <i class="fas fa-money-bill-wave payment-icon"></i>
                                        <div>
                                            <strong>Наличными</strong>
                                            <br><small class="text-muted">При получении</small>
                                        </div>
                                    </button>
                                </div>
                                
                                <!-- Скрытое поле ДОЛЖНО БЫТЬ ВНУТРИ ФОРМЫ -->
                                <input type="hidden" id="selected-payment" name="payment_method" value="sbp" required>
                                
                                <button type="submit" class="btn-order" id="create-order-button" 
HTML;

        // Добавляем атрибут disabled если корзина пуста
        if ($all_sum == 0) {
            $content .= ' disabled';
        }

        $content .= <<<HTML
>
                                    <i class="fas fa-check me-2"></i>
                                    Подтвердить заказ
                                </button>
                            </div>
                            
                            <!-- Дополнительные действия -->
HTML;

        // Кнопка очистки корзины только если есть товары
        if ($all_sum > 0) {
            $content .= <<<HTML
                            <div class="info-card fade-in-up">
                                <div class="text-center">
                                    <form action="/basket_clear" method="POST">
                                        <button type="submit" class="btn-clear">
                                            <i class="fas fa-eraser me-2"></i> Очистить корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
HTML;
        }

        $content .= <<<HTML
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentButtons = document.querySelectorAll('.payment-option');
            const selectedPaymentInput = document.getElementById('selected-payment');
            const orderForm = document.getElementById('order-form');
            const createOrderButton = document.getElementById('create-order-button');

            // Выбираем способ оплаты по умолчанию
            selectedPaymentInput.value = 'sbp';
            paymentButtons[0].classList.remove('btn-light');
            paymentButtons[0].classList.add('btn-primary', 'active');

            paymentButtons.forEach(button => {
                button.addEventListener('click', function () {
                    paymentButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-light');
                    });
                    this.classList.remove('btn-light');
                    this.classList.add('btn-primary', 'active');
                    
                    const paymentMethod = this.getAttribute('data-payment');
                    selectedPaymentInput.value = paymentMethod;
                    console.log('Selected payment method:', paymentMethod);
                });
            });

            orderForm.addEventListener('submit', function (event) {
                if (!selectedPaymentInput.value) {
                    event.preventDefault();
                    const paymentSection = document.querySelector('.sticky-sidebar .info-card');
                    let alertDiv = paymentSection.querySelector('.alert-danger');
                    
                    if (!alertDiv) {
                        alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger mt-3 fade-in-up';
                        alertDiv.innerHTML = `
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Пожалуйста, выберите способ оплаты
                        `;
                        paymentSection.querySelector('.btn-order').before(alertDiv);
                        
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 5000);
                    }
                    
                    alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            // Анимация появления элементов при скролле
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.fade-in-up').forEach(el => {
                observer.observe(el);
            });
        });
        </script>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }

    /**
     * @param array $order
     * @return string
     */
     public static function getOrderDetailsTemplate(array $order): string
    {
        $template = parent::getTemplate();
        $title = 'Детали заказа - Bubble Pizza';

        if (empty($order)) {
            return "Детали заказа не найдены.";
        }

        // Форматируем данные заказа
        $orderId = htmlspecialchars($order['id'] ?? $order['order_id'] ?? '');
        $fio = htmlspecialchars($order['fio'] ?? '');
        $address = htmlspecialchars($order['address'] ?? $order['addres'] ?? '');
        $phone = htmlspecialchars($order['phone'] ?? '');
        $email = htmlspecialchars($order['email'] ?? '');
        $paymentMethod = htmlspecialchars($order['payment_method'] ?? '');
        
        $totalSum = htmlspecialchars($order['all_sum'] ?? $order['total_sum'] ?? $order['sum'] ?? '0');
        $createdAt = htmlspecialchars($order['created_at'] ?? $order['created'] ?? $order['date'] ?? '');
        
        // Преобразуем способ оплаты в читаемый вид
        $paymentMethodName = match($paymentMethod) {
            'sbp' => 'СБП',
            'card' => 'Банковская карта',
            'yandex_pay' => 'Yandex Pay',
            'cash' => 'Наличными при получении',
            default => $paymentMethod ?: 'Не указан'
        };

        // Генерируем HTML для товаров
        $productsHtml = '';
        $calculatedTotal = 0;
        
        $products = $order['products'] ?? $order['items'] ?? [];
        
        if (!empty($products)) {
            foreach ($products as $product) {
                $productName = htmlspecialchars($product['name'] ?? $product['product_name'] ?? 'Неизвестный товар');
                $quantity = intval($product['quantity'] ?? $product['count_item'] ?? $product['count'] ?? 0);
                $price = floatval($product['price'] ?? $product['price_item'] ?? $product['item_price'] ?? 0);
                $total = $quantity * $price;
                $calculatedTotal += $total;
                
                $formattedPrice = number_format($price, 2, '.', ' ');
                $formattedTotal = number_format($total, 2, '.', ' ');
                
                $productsHtml .= <<<HTML
                <div class="product-item fade-in-up">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-1 fw-bold">{$productName}</h6>
                            <small class="text-muted">{$formattedPrice} ₽ × {$quantity} шт.</small>
                        </div>
                        <div class="col-auto">
                            <span class="fw-bold text-success">{$formattedTotal} ₽</span>
                        </div>
                    </div>
                </div>
                <hr class="my-2">
HTML;
            }
        } else {
            $productsHtml = '<div class="text-muted text-center">Товары не найдены</div>';
        }

        // Используем рассчитанную сумму, если оригинальная не найдена
        if ($totalSum == '0' && $calculatedTotal > 0) {
            $totalSum = number_format($calculatedTotal, 2, '.', ' ');
        } else {
            $totalSum = number_format(floatval($totalSum), 2, '.', ' ');
        }

        // Если дата пустая, ставим текущую
        if (empty($createdAt)) {
            $createdAt = date('d-m-Y H:i:s');
        }

        $content = <<<HTML
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        .order-details-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 2rem;
        }
        
        .order-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .order-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d09db0, #667eea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .order-number {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102,126,234,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title {
            background: linear-gradient(135deg, #d09db0, #667eea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .total-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .product-item {
            padding: 0.75rem 0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            align-items: center;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 150px;
        }
        
        .info-value {
            color: #333;
            text-align: right;
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn-back {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(102,126,234,0.2);
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-back:hover {
            border-color: #667eea;
            transform: translateY(-1px);
            color: #667eea;
        }
        </style>

        <div class="order-details-container">
            <div class="order-header fade-in-up">
                <h1>Детали заказа</h1>
                <div class="order-number">
                    <i class="fas fa-receipt me-2"></i>
                    Номер заказа: #{$orderId}
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="info-card fade-in-up">
                        <h3 class="section-title">
                            <i class="fas fa-truck"></i>Информация о доставке
                        </h3>
                        <div class="info-row">
                            <span class="info-label">ФИО:</span>
                            <span class="info-value">{$fio}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Адрес:</span>
                            <span class="info-value">{$address}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Телефон:</span>
                            <span class="info-value">{$phone}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{$email}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Способ оплаты:</span>
                            <span class="info-value">{$paymentMethodName}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Дата заказа:</span>
                            <span class="info-value">{$createdAt}</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="info-card fade-in-up">
                        <h3 class="section-title">
                            <i class="fas fa-pizza-slice"></i>Состав заказа
                        </h3>
                        <div class="products-list">
                            {$productsHtml}
                        </div>
                    </div>
                    
                    <div class="total-card fade-in-up">
                        <h3 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Итого: {$totalSum} ₽
                        </h3>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4 fade-in-up">
                <a href="/history" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Назад к истории заказов
                </a>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Анимация появления элементов при скролле
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.fade-in-up').forEach(el => {
                observer.observe(el);
            });
        });
        </script>
HTML;
        
        return sprintf($template, $title, $content);
    }
}