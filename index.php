<?php 
// ПЕРВЫМ делом - автозагрузчик Composer
require_once(__DIR__ . '/vendor/autoload.php');



use App\Router\Router;

session_start();

// Обновляем глобальные переменные - данными из сессии
$user_id = 0;
$username = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$router = new Router();
$url = $_SERVER['REQUEST_URI'];
echo $router->route($url);