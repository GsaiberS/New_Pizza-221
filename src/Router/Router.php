<?php
namespace App\Router;

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;
use App\Controllers\RegisterController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Services\UserDBStorage;

class Router {
    public function route(string $url): string {
        global $user_id, $username, $avatar, $user_role;

        // 🔥 ОТЛАДКА
        error_log("=== ROUTER DEBUG ===");
        error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
        error_log("Session role: " . ($_SESSION['role'] ?? 'NOT SET'));
        error_log("Session username: " . ($_SESSION['username'] ?? 'NOT SET'));

        if (isset($_SESSION['user_id'])) {
            $userStorage = new UserDBStorage();
            $userData = $userStorage->getUserById((int)$_SESSION['user_id']);
            
            // 🔥 ОТЛАДКА ДАННЫХ ИЗ БАЗЫ
            error_log("User data from DB:");
            error_log("DB Role: " . ($userData['role'] ?? 'NOT SET'));
            error_log("DB Username: " . ($userData['username'] ?? 'NOT SET'));
            
            $user_id = $_SESSION['user_id'];
            $username = $userData['username'] ?? '';
            $avatar = $userData['avatar'] ?? '/assets/image/default-avatar.png';
            
            // 🔥 ИСПОЛЬЗУЕМ РОЛЬ ИЗ СЕССИИ (ПРИОРИТЕТ) ИЛИ ИЗ БАЗЫ
            $user_role = $_SESSION['role'] ?? $userData['role'] ?? 'user';
            
            error_log("Final user_role: " . $user_role);
        } else {
            $user_id = 0;
            $username = '';
            $avatar = '/assets/image/default-avatar.png';
            $user_role = 'guest';
        }

        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1] ?? '';
        
        // 🔥 ОТЛАДКА ДЛЯ АДМИН ПАНЕЛИ
        if (strpos($path, '/admin') === 0) {
            error_log("ADMIN ACCESS CHECK:");
            error_log("Requested path: " . $path);
            error_log("User role: " . $user_role);
            error_log("Is admin: " . ($user_role === 'admin' ? 'YES' : 'NO'));
            error_log("User ID: " . $user_id);
            error_log("Username: " . $username);
        }

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
                case 'admin':
                    // 🔥 ПРОВЕРКА ПРАВ ДЛЯ POST ЗАПРОСОВ
                    if ($user_role !== 'admin') {
                        error_log("ADMIN POST ACCESS DENIED: User role is '{$user_role}'");
                        $_SESSION['flash'] = "Доступ запрещен: недостаточно прав";
                        header("Location: /");
                        exit();
                    }
                    $adminController = new AdminController();
                    $subroute = $pieces[2] ?? null;
                    
                    if ($subroute === 'update_user') {
                        $adminController->updateUser();
                    } elseif ($subroute === 'delete_user' && isset($pieces[3])) {
                        $_GET['id'] = (int)$pieces[3];
                        $adminController->delete();
                    }
                    header("Location: /admin");
                    exit();
            }
            exit(); 
        }

        // GET запросы
        if ($resource === "order" && isset($pieces[2]) && is_numeric($pieces[2])) {
            $orderId = (int)$pieces[2];
            return $orderController->getDetails($orderId);
        }

        // Исправлено: products использует pieces[2] для ID
        if ($resource === "products" && isset($pieces[2]) && is_numeric($pieces[2])) {
            $id = intval($pieces[2]);
            $productController = new ProductController();
            return $productController->get($id);
        }

        switch ($resource) {
            case "admin":
                // 🔥 ПРОВЕРКА ПРАВ ДЛЯ GET ЗАПРОСОВ
                if ($user_role !== 'admin') {
                    error_log("ADMIN GET ACCESS DENIED: User role is '{$user_role}'");
                    $_SESSION['flash'] = "Доступ запрещен: недостаточно прав";
                    header("Location: /");
                    exit();
                }

                error_log("ADMIN ACCESS GRANTED");
                $adminController = new AdminController();
                $subroute = $pieces[2] ?? null;

                if ($subroute === 'edit_user' && isset($pieces[3]) && is_numeric($pieces[3])) {
                    $_GET['id'] = (int)$pieces[3];
                    return $adminController->editUser();
                } else {
                    return $adminController->index();
                }
                break;
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
                $token = $pieces[2] ?? null;
                return $registerController->verify($token);
            case "history":
                return $userController->getOrdersHistory();
            case "login":
                return $userController->get();
            case "logout":
                unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
                session_destroy();
                header("Location: /");
                exit();
            case "products":
                $productController = new ProductController();
                $id = isset($pieces[2]) ? intval($pieces[2]) : null;
                return $productController->get($id);
            case "profile":
                return $userController->profile();
            default:
                $home = new HomeController();
                return $home->get();
        }
        return '';
    }
}