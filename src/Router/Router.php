<?php
namespace App\Router;

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;
use App\Controllers\RegisterController;
use App\Controllers\UserController;
use App\Services\UserDBStorage;

class Router {
    public function route(string $url): string {
        // Инициализация глобальных переменных
        global $user_id, $username, $avatar;

        if (isset($_SESSION['user_id'])) {
            $userStorage = new UserDBStorage();
            $userData = $userStorage->getUserById((int)$_SESSION['user_id']);
            $user_id = $_SESSION['user_id'];
            $username = $userData['username'] ?? '';
            $avatar = $userData['avatar'] ?? 'path/to/default/avatar.png'; // Дефолтный аватар
        } else {
            $user_id = 0;
            $username = '';
            $avatar = 'path/to/default/avatar.png';
        }

        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];

        // --- ИЗМЕНЕННАЯ ЛОГИКА ДЛЯ МАРШРУТОВ "order" и "products" ---
        // Сначала проверяем более специфичные маршруты с ID.
        // Это важно, так как URL /order/123 должен быть обработан иначе, чем /order
        if ($resource === "order" && isset($pieces[2]) && is_numeric($pieces[2])) {
            $orderId = (int)$pieces[2];
            $orderController = new OrderController();
            return $orderController->getDetails($orderId); // Новый метод для деталей заказа
        }

        // Этот блок остаётся как есть, так как он проверяет специфичный URL
        if ($resource === "products" && isset($pieces[3])) { // Проверяем, что есть ID
            $id = intval($pieces[3]);
            $productController = new ProductController();
            return $productController->get($id);
        }

        switch ($resource) {
            case "about":
                $about = new AboutController();
                return $about->get();
            case "order":
                // Этот блок теперь обрабатывает ТОЛЬКО /order (корзину)
                $orderController = new OrderController();
                return $orderController->get();
            case "register":
                $registerController = new RegisterController();
                return $registerController->get();
            case "verify":
                $registerController = new RegisterController();
                $token = (isset($pieces[2])) ? $pieces[2] : null;
                return $registerController->verify($token);
            case "history":
                $userController = new UserController();
                return $userController->getOrdersHistory();
            case "login":
                $userController = new UserController();
                return $userController->get();
            case "logout":
                unset($_SESSION['user_id']);
                unset($_SESSION['username']);
                session_destroy();
                header("Location: /");
                return "";
            case 'basket_clear':
                $basketController = new BasketController();
                $basketController->clear();
                $prevUrl = $_SERVER['HTTP_REFERER'];
                header("Location: {$prevUrl}");
                return '';
            case "products":
                $productController = new ProductController();
                $id = (isset($pieces[3])) ? intval($pieces[3]) : null;
                return $productController->get($id);
            case "basket":
                $basketController = new BasketController();
                $basketController->add();
                $prevUrl = $_SERVER['HTTP_REFERER'];
                header("Location: {$prevUrl}");
                return "";
            case "profile":
                $userController = new UserController();

                // Проверяем метод запроса
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Если POST-запрос, обновляем данные профиля
                    $userController->updateProfile();
                } else {
                    // Если GET-запрос, отображаем страницу профиля
                    return $userController->profile();
                }
                break;
            default:
                $home = new HomeController();
                return $home->get();
        }
    }
}