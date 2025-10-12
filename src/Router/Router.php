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
        global $user_id, $username, $avatar;

        if (isset($_SESSION['user_id'])) {
            $userStorage = new UserDBStorage();
            $userData = $userStorage->getUserById((int)$_SESSION['user_id']);
            $user_id = $_SESSION['user_id'];
            $username = $userData['username'] ?? '';
            $avatar = $userData['avatar'] ?? '/assets/image/default-avatar.png';
        } else {
            $user_id = 0;
            $username = '';
            $avatar = '/assets/image/default-avatar.png';
        }

        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];
        
        $userController = new UserController();
        $basketController = new BasketController();
        $orderController = new OrderController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($resource) {
                case 'basket':
                    $basketController->add();
                    break; 
                case 'basket_remove':
                    $basketController->remove();
                    break;
                case 'basket_increase':
                    $basketController->increase();
                    break;
                case 'basket_decrease':
                    $basketController->decrease();
                    break;
                case 'basket_clear':
                    $basketController->clear();
                    break;
                case 'profile':
                    // ИСПРАВЛЕНИЕ: вызываем updateProfile для POST запросов на /profile
                    $userController->updateProfile();
                    break;
                case 'register':
                    $registerController = new RegisterController();
                    $registerController->post();
                    break;
                case 'login':
                    $userController->login();
                    break;
                case 'order':
                    $orderController->create();
                    break;
            }
            exit(); 
        }

        // GET запросы
        if ($resource === "order" && isset($pieces[2]) && is_numeric($pieces[2])) {
            $orderId = (int)$pieces[2];
            return $orderController->getDetails($orderId);
        }

        if ($resource === "products" && isset($pieces[3])) {
            $id = intval($pieces[3]);
            $productController = new ProductController();
            return $productController->get($id);
        }

        switch ($resource) {
            case "about":
                $about = new AboutController();
                return $about->get();
            case "order":
                return $orderController->get();
            case "register":
                $registerController = new RegisterController();
                if (isset($pieces[2])) {
                    switch ($pieces[2]) {
                        case "google":
                            $registerController->googleAuth();
                            exit;
                        case "vk":
                            $registerController->vkAuth();
                            exit;
                        case "yandex":
                            $registerController->yandexAuth();
                            exit;
                        case "steam":
                            $registerController->steamAuth();
                            exit;
                    }
                }
                return $registerController->get();
            case "verify":
                $registerController = new RegisterController();
                $token = (isset($pieces[2])) ? $pieces[2] : null;
                return $registerController->verify($token);
            case "history":
                return $userController->getOrdersHistory();
            case "login":
                return $userController->get();
            case "logout":
                unset($_SESSION['user_id']);
                unset($_SESSION['username']);
                session_destroy();
                header("Location: /");
                exit();
            case "products":
                $productController = new ProductController();
                $id = (isset($pieces[3])) ? intval($pieces[3]) : null;
                return $productController->get($id);
            case "profile":
                // ИСПРАВЛЕНИЕ: для GET запросов на /profile показываем форму
                return $userController->profile();
            default:
                $home = new HomeController();
                return $home->get();
        }
        return '';
    }
}