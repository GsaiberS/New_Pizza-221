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
        
        $userController = new UserController();
        $basketController = new BasketController();

        // ----------------------------------------------------
        // --- ГАРАНТИЯ: ИЗОЛИРОВАННАЯ ОБРАБОТКА ВСЕХ POST-ЗАПРОСОВ ---
        // ----------------------------------------------------
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($resource) {
                case 'basket':
                    $basketController->add();
                    // add() делает редирект и exit()
                    break; 
                case 'basket_remove':
                    // Оставляем этот кейс для полного удаления товара из корзины (все количества)
                    $basketController->remove();
                    // remove() делает редирект и exit()
                    break;
                case 'basket_increase':
                    $basketController->increase();
                    // increase() должен делать редирект и exit()
                    break;
                case 'basket_decrease':
                    // НОВЫЙ КЕЙС: Уменьшает количество товара на 1
                    $basketController->decrease();
                    // decrease() должен делать редирект и exit()
                    break;
                case 'basket_clear':
                    $basketController->clear();
                    // clear() делает редирект и exit()
                    break;
                case 'profile':
                    $userController->updateProfile();
                    // updateProfile() должен делать редирект и exit()
                    break;
                
                // --- ДОБАВЛЕННЫЕ POST-МАРШРУТЫ ДЛЯ АУТЕНТИФИКАЦИИ ---
                case 'register':
                    $registerController = new RegisterController();
                    $registerController->post();
                    // post() должен обрабатывать форму регистрации и делать редирект/exit()
                    break;
                case 'login':
                    $userController->login();
                    // login() должен обрабатывать форму входа и делать редирект/exit()
                    break;
                // ------------------------------------------------------
            }
            // Гарантируем выход, чтобы избежать дальнейшего провала в логику GET-маршрутов.
            exit(); 
        }
        // ----------------------------------------------------


        // --- ОБРАБОТКА GET-ЗАПРОСОВ И СПЕЦИФИЧНЫХ URL С ID ---
        // Сначала проверяем более специфичные маршруты с ID.
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
                // $userController уже инициализирован
                return $userController->getOrdersHistory();
            case "login":
                // $userController уже инициализирован. Отображает форму входа.
                return $userController->get();
            case "logout":
                unset($_SESSION['user_id']);
                unset($_SESSION['username']);
                session_destroy();
                header("Location: /");
                exit(); // !!! Корректный выход
            
            case "products":
                $productController = new ProductController();
                $id = (isset($pieces[3])) ? intval($pieces[3]) : null;
                return $productController->get($id);

            case "profile":
                // Теперь обрабатываем только GET-запрос для страницы профиля
                // $userController уже инициализирован
                return $userController->profile();
                
            default:
                $home = new HomeController();
                return $home->get();
        }
        return '';
    }
}
