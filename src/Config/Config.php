<?php

namespace App\Config;

use Dotenv\Dotenv;

class Config 
{
    // Инициализация переменных окружения
    public static function loadEnv(): void {
       $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Два уровня выше — в корень проекта
        $dotenv->load();
    }

    // --- Локальные файлы ---
    const FILE_PRODUCTS = ".\\Storage\\data.json";
    const FILE_ORDERS = ".\\Storage\\order.json";

    // --- Типы хранилища ---
    const TYPE_FILE = "file";
    const TYPE_DB = "db";
    const STORAGE_TYPE = self::TYPE_DB;

    // --- Настройки подключения к БД ---
    const MYSQL_DNS = 'mysql:dbname=pizzais;host=localhost;charset=utf8';
    const MYSQL_USER = 'root';
    const MYSQL_PASSWORD = '';
    const TABLE_PRODUCTS = "products";
    const TABLE_ORDERS = "orders";

    // --- Основные настройки сайта ---
    const SITE_URL = "http://localhost";
    public static function isGoogleOAuthConfigured(): bool {
        return !empty($_ENV['GOOGLE_CLIENT_ID']) && !empty($_ENV['GOOGLE_CLIENT_SECRET']);
    }
    // Загрузка переменных из .env
    public static function getGoogleClientId(): string {
        
        return $_ENV['GOOGLE_CLIENT_ID'] ?? '';
    }

    public static function getGoogleClientSecret(): string {
        return $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
    }

    const GOOGLE_REDIRECT = self::SITE_URL . '/callback/google';

    const VK_CLIENT_ID = '';     
    const VK_CLIENT_SECRET = ''; 
    const VK_REDIRECT = self::SITE_URL . '/callback/vk';

    const STEAM_API_KEY = '';    
    const STEAM_REDIRECT = self::SITE_URL . '/callback/steam';

    // --- Статусы заказов ---
    public const CODE_STATUS = [
        "без статуса",
        "в работе",
        "доставляется",
        "завершен"
    ];

    public static function getStatusName(int $code): string {
        return self::CODE_STATUS[$code] ?? "неизвестно";
    }

    public static function getStatusColor(int $code): string {
        $colors = [
            0 => 'text-secondary',
            1 => 'text-primary',
            2 => 'text-warning',
            3 => 'text-success'
        ];
        return $colors[$code] ?? 'text-dark';
    }

    // --- Конфигурация для Hybridauth ---
    public static function getHybridConfig(): array {
        self::loadEnv(); // Загружаем переменные окружения

        return [
            'callback' => self::GOOGLE_REDIRECT,
            'providers' => [
                'Google' => [
                    'enabled' => true,
                    'keys' => [
                        'id' => self::getGoogleClientId(),
                        'secret' => self::getGoogleClientSecret(),
                    ],
                    'scope' => 'email profile',
                ],
            ],
        ];
    }
}