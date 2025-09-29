<?php

namespace App\Controllers;

class BasketController
{
    /**
     * Добавляет товар в корзину (или увеличивает количество).
     */
    public function add(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['id'])) {
            $product_id = (int)$_POST['id'];
            
            if (!isset($_SESSION['basket'])) {
                $_SESSION['basket'] = [];
            }

            if (isset($_SESSION['basket'][$product_id])) {
                $_SESSION['basket'][$product_id]['quantity']++;
            } else {
                $_SESSION['basket'][$product_id] = [
                    'quantity' => 1
                ];
            }
            $_SESSION['flash'] = "Товар успешно добавлен в корзину!";
            
            // Здесь оставляем редирект на HTTP_REFERER, так как добавление может быть с разных страниц товаров.
            $prevUrl = $_SERVER['HTTP_REFERER'] ?? '/products'; 
            header("Location: {$prevUrl}");
            exit();
        }
        
        header("Location: /products");
        exit();
    }

    /**
     * Увеличивает количество товара в корзине.
     */
    public function increase(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['id']) && isset($_SESSION['basket'])) {
            $product_id = (int)$_POST['id'];
            if (isset($_SESSION['basket'][$product_id])) {
                $_SESSION['basket'][$product_id]['quantity']++;
                $_SESSION['flash'] = "Количество товара увеличено.";
            } else {
                $_SESSION['flash'] = "Ошибка: Товар не найден в корзине для увеличения количества.";
            }
        } else {
            $_SESSION['flash'] = "Ошибка: Не удалось определить ID товара.";
        }
        
        // Редирект всегда на страницу заказа/корзины
        header("Location: /order");
        exit();
    }

    /**
     * Уменьшает количество товара в корзине.
     * Если количество становится 0, товар удаляется.
     */
    public function decrease(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['id']) && isset($_SESSION['basket'])) {
            $product_id = (int)$_POST['id'];
            if (isset($_SESSION['basket'][$product_id])) {
                
                if ($_SESSION['basket'][$product_id]['quantity'] > 1) {
                    // Если количество > 1, просто уменьшаем
                    $_SESSION['basket'][$product_id]['quantity']--;
                    $_SESSION['flash'] = "Количество товара уменьшено.";
                } else {
                    // Если количество равно 1, удаляем позицию, как при полном удалении
                    unset($_SESSION['basket'][$product_id]);
                    $_SESSION['flash'] = "Позиция удалена из корзины.";
                }
            } else {
                 $_SESSION['flash'] = "Ошибка: Товар не найден в корзине для уменьшения количества.";
            }
        } else {
            // Если ID не пришел, это очень важная диагностическая информация.
            $_SESSION['flash'] = "Ошибка: Не удалось определить ID товара для уменьшения количества. Проверьте форму первого товара!";
        }
        
        // Редирект всегда на страницу заказа/корзины
        header("Location: /order");
        exit();
    }
    
    /**
     * Удаляет один товар из корзины по его ID.
     */
    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_POST['id']) && isset($_SESSION['basket'])) {
            $product_id_to_remove = (int)$_POST['id'];

            if (isset($_SESSION['basket'][$product_id_to_remove])) {
                unset($_SESSION['basket'][$product_id_to_remove]);
                $_SESSION['flash'] = "Позиция успешно удалена из корзины.";
            } else {
                $_SESSION['flash'] = "Ошибка: Товар не найден в корзине для полного удаления.";
            }
        } else {
             $_SESSION['flash'] = "Ошибка: Не удалось определить ID товара для полного удаления.";
        }
        
        // Редирект всегда на страницу заказа/корзины
        header("Location: /order");
        exit();
    }
    
    /**
     * Очистка корзины.
     */
    public function clear(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['basket'] = [];
        $_SESSION['flash'] = "Корзина успешно очищена.";
        
        // Редирект всегда на страницу заказа/корзины
        header("Location: /order");
        exit();
    }
}
