<?php 
namespace App\Controllers;

use App\Models\Product;
use App\Services\ProductDBStorage;
use App\Views\ProductTemplate;
use App\Services\FileStorage;
use App\Services\DatabaseStorage;
use App\Config\Config;
use App\Services\ISaveStorage;

class ProductController {
    public function get(?int $id): string {

        // Убедимся, что сессия запущена, прежде чем обращаться к $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceStorage = new ProductDBStorage();
            $model = new Product($serviceStorage, Config::TABLE_PRODUCTS);
        }

        // Загружаем данные
        $data = $model->loadData();

        // Проверяем, что данные успешно загружены
        if ($data === null || !is_array($data)) {
            return "Ошибка загрузки данных.";
        }

        // ****************************************************
        // *** ЛОГИКА ДЛЯ ПОЛУЧЕНИЯ КОЛИЧЕСТВА ТОВАРОВ В КОРЗИНЕ ***
        // ****************************************************
        $basketCount = 0;
        if (isset($_SESSION['basket']) && is_array($_SESSION['basket'])) {
            foreach ($_SESSION['basket'] as $item) {
                // Предполагаем, что количество хранится в 'count_item' или 'quantity'
                $basketCount += (int)($item['count_item'] ?? $item['quantity'] ?? 1);
            }
        }
        
        // ДЛЯ ОТЛАДКИ: выведем информацию о корзине
        error_log("Basket count: " . $basketCount);
        error_log("Basket session data: " . print_r($_SESSION['basket'] ?? 'empty', true));
        // ****************************************************
        
        // Если ID не указан, возвращаем все товары
        if (!isset($id)) {
            // !!! ГЛАВНОЕ: Передаем счетчик корзины в шаблон каталога
            return ProductTemplate::getAllTemplate($data, $basketCount);
        }

        // Если ID указан, проверяем его корректность
        if (($id) && ($id <= count($data))) {
            $record = $data[$id - 1];
            return ProductTemplate::getCardTemplate($record);
        } else {
            return ProductTemplate::getCardTemplate(null);
        }
    }
    // В BasketController
public function addToBasket(): void {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    
    $productId = $_POST['id'] ?? null;
    if ($productId) {
        // Добавляем товар в корзину
        $_SESSION['basket'][$productId] = [
            'id' => $productId,
            'quantity' => ($_SESSION['basket'][$productId]['quantity'] ?? 0) + 1
        ];
    }
    
    header('Location: /products');
    exit;
}
}