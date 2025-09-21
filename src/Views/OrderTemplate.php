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
        $title = 'Создание заказа';
        $username = htmlspecialchars($userData['username'] ?? '');
        $email = htmlspecialchars($userData['email'] ?? '');
        $address = htmlspecialchars($userData['address'] ?? '');
        $phone = htmlspecialchars($userData['phone'] ?? '');

        $content = <<<HTML
        <h1 class="text-center mb-5">Создание заказа</h1>
        <div class="row">
            <div class="col-md-8" data-aos="fade-right">
                <h3 class="mb-4">Корзина</h3>
HTML;

        $all_sum = 0;

        if (!empty($arr)) {
            foreach ($arr as $product) {
                // ИСПРАВЛЕНИЕ: ваш шаблон корзины ожидает 'name', 'price' и 'quantity'
                // но получает 'name', 'price' и 'count_item' из BasketDBStorage
                $name = htmlspecialchars($product['name']);
                $price = htmlspecialchars($product['price']);
                $quantity = htmlspecialchars($product['quantity']);

                $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/assets/image/{$product['id']}.png";
                $image = file_exists($imagePath)
                    ? "/assets/image/{$product['id']}.png"
                    : '/assets/image/default.png';

                $sum = $price * $quantity;
                $all_sum += $sum;

                $content .= <<<LINE
                <div class="card mb-3 product-card-animated" data-aos="fade-up">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-3 text-center">
                            <img src="{$image}" class="img-fluid rounded-start product-image" alt="{$name}">
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title mb-1">{$name}</h5>
                                <p class="card-text mb-1"><small class="text-muted">Цена за единицу: {$price} ₽</small></p>
                                <p class="card-text mb-1">Количество: {$quantity} ед.</p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <p class="card-text mb-0 fw-bold">Итого: <span class="text-success">{$sum} ₽</span></p>
                                    <form action="/basket_remove" method="POST">
                                        <input type="hidden" name="id" value="{$product['id']}">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt me-1"></i> Удалить
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
            <div class="card mb-3 total-sum-card" data-aos="fade-up">
                <div class="card-body text-end">
                    <h5 class="card-title mb-0">Общая сумма: <strong class="text-success">{$all_sum} ₽</strong></h5>
                </div>
            </div>
HTML;
        } else {
            $content .= <<<HTML
            <div class="alert alert-info text-center" role="alert" data-aos="fade-up">
                Ваша корзина пуста.
            </div>
HTML;
        }

        $content .= <<<HTML
            </div>
            <div class="col-md-4" data-aos="fade-left">
                <div class="sticky-top order-summary">
                    <h4 class="text-center mb-4">Способ оплаты</h4>
                    <div class="d-flex flex-column gap-3">
                        <button type="button" class="btn btn-outline-primary payment-option w-100" data-payment="sbp">
                            <i class="fas fa-qrcode"></i> По СБП
                        </button>
                        <button type="button" class="btn btn-outline-primary payment-option w-100" data-payment="card">
                            <i class="fas fa-credit-card"></i> По номеру карты
                        </button>
                        <button type="button" class="btn btn-outline-primary payment-option w-100" data-payment="yandex_pay">
                            <i class="fab fa-yandex"></i> Yandex Pay
                        </button>
                        <button type="button" class="btn btn-outline-primary payment-option w-100" data-payment="cash">
                            <i class="fas fa-money-bill-wave"></i> При получении
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 order-form-card" data-aos="fade-up">
            <div class="card-body">
                <h4 class="text-center mb-4">Оформление заказа</h4>
                <form action="/order" method="POST" id="order-form">
                    <div class="mb-3">
                        <label for="fio" class="form-label">Ваше ФИО:</label>
                        <input type="text" class="form-control" value="{$username}" id="fio" name="fio" placeholder="Введите ваше ФИО" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" value="{$email}" id="email" name="email" placeholder="Введите ваш email" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Адрес доставки:</label>
                        <input type="text" class="form-control" value="{$address}" id="address" name="address" placeholder="Введите адрес доставки">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон:</label>
                        <input type="tel" class="form-control" value="{$phone}" id="phone" name="phone" placeholder="Введите номер телефона">
                    </div>
                    <input type="hidden" id="selected-payment" name="payment_method" value="">
                    <button type="submit" class="btn btn-custom w-100" id="create-order-button">
                        <i class="fas fa-shopping-cart me-2"></i> Создать заказ
                    </button>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <form action="/basket_clear" method="POST">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="fas fa-eraser me-2"></i> Очистить корзину
                </button>
            </form>
        </div>

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 800,
                once: true
            });

            document.addEventListener('DOMContentLoaded', function () {
                const paymentButtons = document.querySelectorAll('.payment-option');
                const selectedPaymentInput = document.getElementById('selected-payment');
                const orderForm = document.getElementById('order-form');
                const createOrderButton = document.getElementById('create-order-button');
                const cartItems = document.querySelectorAll('.product-card-animated');

                if (cartItems.length === 0) {
                    createOrderButton.disabled = true;
                    createOrderButton.classList.add('disabled');
                }

                paymentButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        paymentButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        const paymentMethod = this.getAttribute('data-payment');
                        selectedPaymentInput.value = paymentMethod;
                    });
                });

                orderForm.addEventListener('submit', function (event) {
                    if (!selectedPaymentInput.value) {
                        event.preventDefault();
                        alert('Пожалуйста, выберите способ оплаты.');
                    }
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
        $title = 'Детали заказа';

        if (empty($order)) {
            return "Детали заказа не найдены.";
        }
        
        $paymentMethodName = match($order['payment_method']) {
            'sbp' => 'СБП',
            'card' => 'По номеру карты',
            'yandex_pay' => 'Yandex Pay',
            'cash' => 'При получении',
            default => 'Не указан'
        };

        $productsHtml = '';
        foreach ($order['products'] as $product) {
            // ИСПРАВЛЕНИЕ: используем 'id', 'count_item' и 'price_item'
            $productsHtml .= <<<HTML
            <li class="list-group-item d-flex justify-content-between align-items-center product-item-animated" data-aos="fade-up">
                <div class="d-flex align-items-center">
                    <i class="fas fa-pizza-slice me-3 text-info"></i>
                    <span>{$product['name']}</span>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary rounded-pill me-2">{$product['count_item']} шт.</span>
                    <span class="text-success fw-bold">{$product['price_item']} ₽</span>
                </div>
            </li>
HTML;
        }

        $historyHtml = '';
        if (!empty($order['history'])) {
            foreach ($order['history'] as $statusEntry) {
                // Если у вас нет таблицы 'order_history', этот код всё равно не сработает
                $statusName = \Config::getStatusName($statusEntry['status']);
                $time = date("d.m.Y H:i", strtotime($statusEntry['timestamp']));
                $historyHtml .= <<<HTML
                <li class="list-group-item status-item-animated" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-circle me-2 text-info"></i>
                    <span class="fw-bold">{$statusName}</span>
                    <br><small class="text-muted">{$time}</small>
                </li>
HTML;
            }
        } else {
            $historyHtml .= "<li class='list-group-item text-muted' data-aos='fade-up'>История статусов не найдена.</li>";
        }

        $content = <<<HTML
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
            body { font-family: 'Poppins', sans-serif; background-color: #f9f9f9; color: #333; }
            .modal-content { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            .modal-header { border-top-left-radius: 15px; border-top-right-radius: 15px; background: linear-gradient(45deg, #d09db0, #e6b4c3); color: white; }
            .modal-title { font-weight: 600; }
            .btn-close-white { filter: invert(1); }
            .order-details-info p { margin-bottom: 0.5rem; }
            .order-details-info strong { color: #555; }
            .list-group-item { border: none; padding: 1rem 0; transition: background-color 0.3s ease; }
            .list-group-item:not(:last-child) { border-bottom: 1px solid #eee; }
            .list-group-item:hover { background-color: #f5f5f5; }
            .section-header { border-bottom: 2px solid #e6b4c3; padding-bottom: 5px; margin-bottom: 15px; }
            .section-header h6 { color: #d09db0; font-weight: 600; }
            [data-aos] { transition: transform 0.6s ease, opacity 0.6s ease; }
            [data-aos="fade-up"] { transform: translateY(20px); opacity: 0; }
            [data-aos="fade-right"] { transform: translateX(-20px); opacity: 0; }
            [data-aos="fade-left"] { transform: translateX(20px); opacity: 0; }
            .aos-animate { transform: none; opacity: 1; }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card p-4" data-aos="zoom-in" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <div class="card-header border-0 bg-transparent text-center pb-0">
                            <h2 class="fw-bold mb-0" style="color: #d09db0;">Детали заказа №{$order['id']}</h2>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6" data-aos="fade-right">
                                    <div class="section-header">
                                        <h6 class="text-primary"><i class="fas fa-truck-moving me-2"></i>Информация о доставке</h6>
                                    </div>
                                    <div class="order-details-info">
                                        <p><strong>Адрес:</strong> {$order['addres']}</p>
                                        <p><strong>Телефон:</strong> {$order['phone']}</p>
                                        <p><strong>Способ оплаты:</strong> {$paymentMethodName}</p>
                                        <p><strong>Сумма:</strong> <span class="fw-bold fs-5 text-success">{$order['all_sum']} ₽</span></p>
                                    </div>
                                </div>
                                <div class="col-md-6" data-aos="fade-left">
                                    <div class="section-header">
                                        <h6 class="text-primary"><i class="fas fa-history me-2"></i>История статусов</h6>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        {$historyHtml}
                                    </ul>
                                </div>
                            </div>
                            
                            <hr class="my-4" data-aos="fade-up">

                            <div data-aos="fade-up">
                                <div class="section-header text-center">
                                    <h6 class="text-primary"><i class="fas fa-pizza-slice me-2"></i>Состав заказа</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    {$productsHtml}
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center pt-0" data-aos="fade-up">
                            <a href="mailto:support@pizzeria.ru" class="btn btn-custom">
                                <i class="fas fa-headset me-2"></i>Связаться с техподдержкой
                            </a>
                            <a href="/history" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Назад к заказам
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 800,
                once: true,
                easing: 'ease-in-out'
            });
        </script>
HTML;
        return sprintf($template, $title, $content);
    }
}