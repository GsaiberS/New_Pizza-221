<?php

namespace App\Config;

use Dotenv\Dotenv;

class Config 
{
    // Инициализация переменных окружения
    public static function loadEnv(): void {
        $path = __DIR__ . '/../../';
        error_log("Loading .env from: " . $path);
        
        if (!file_exists($path . '.env')) {
            error_log(".env file not found at: " . $path . '.env');
        } else {
            error_log(".env file found");
        }

        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
        
        // ОТЛАДКА: Проверяем загрузку переменных
        error_log("VK_CLIENT_ID loaded: " . (isset($_ENV['VK_CLIENT_ID']) ? $_ENV['VK_CLIENT_ID'] : 'NOT FOUND'));
        error_log("VK_CLIENT_SECRET loaded: " . (isset($_ENV['VK_CLIENT_SECRET']) ? substr($_ENV['VK_CLIENT_SECRET'], 0, 5) . '...' : 'NOT FOUND'));
        error_log("STEAM_API_KEY loaded: " . (isset($_ENV['STEAM_API_KEY']) ? 'LOADED' : 'NOT FOUND'));
    }

    // Автоматическая инициализация при загрузке класса
    public static function init() {
        self::loadEnv();
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

    // Загрузка переменных из .env с отладкой
    public static function getGoogleClientId(): string {
        $clientId = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
        error_log("Google ClientID from env: " . $clientId);
        return $clientId;
    }

    public static function getGoogleClientSecret(): string {
        $secret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
        error_log("Google ClientSecret from env: " . (!empty($secret) ? 'LOADED' : 'EMPTY'));
        return $secret;
    }

    // VK OAuth настройки через .env с отладкой
    public static function getVkClientId(): string {
        $clientId = $_ENV['VK_CLIENT_ID'] ?? '';
        error_log("VK ClientID from env: " . $clientId);
        
        // ВРЕМЕННЫЙ ХАРДКОД ДЛЯ ТЕСТА - удалить после проверки
        if (empty($clientId)) {
            error_log("Using hardcoded VK ClientID");
            return '54230789';
        }
        
        return $clientId;
    }

    public static function getVkClientSecret(): string {
        $secret = $_ENV['VK_CLIENT_SECRET'] ?? '';
        error_log("VK ClientSecret from env: " . (!empty($secret) ? 'LOADED' : 'EMPTY'));
        
        // ВРЕМЕННЫЙ ХАРДКОД ДЛЯ ТЕСТА - удалить после проверки
        if (empty($secret)) {
            error_log("Using hardcoded VK ClientSecret");
            return 'U0FiqdIIo9Gkc884ObDe';
        }
        
        return $secret;
    }

    // Steam OAuth настройки
    public static function getSteamApiKey(): string {
        $apiKey = $_ENV['STEAM_API_KEY'] ?? '';
        error_log("Steam API Key from env: " . (!empty($apiKey) ? 'LOADED' : 'EMPTY'));
        return $apiKey;
    }

    // Redirect URLs
    const GOOGLE_REDIRECT = self::SITE_URL . '/register/google';
    const VK_REDIRECT = self::SITE_URL . '/register/vk';
    const STEAM_REDIRECT = self::SITE_URL . '/register/steam';

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
        self::loadEnv();

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

    // --- Конфигурация для Steam через Hybridauth ---
    public static function getSteamConfig(): array {
        self::loadEnv();

        return [
            'callback' => self::STEAM_REDIRECT,
            'providers' => [
                'Steam' => [
                    'enabled' => true,
                    'keys' => [
                        'secret' => self::getSteamApiKey(),
                    ],
                ],
            ],
        ];
    }

    // --- Общая конфигурация для всех провайдеров ---
    public static function getAllOAuthConfig(): array {
        self::loadEnv();

        return [
            'callback' => self::SITE_URL,
            'providers' => [
                'Google' => [
                    'enabled' => true,
                    'keys' => [
                        'id' => self::getGoogleClientId(),
                        'secret' => self::getGoogleClientSecret(),
                    ],
                    'scope' => 'email profile',
                ],
                'Steam' => [
                    'enabled' => !empty(self::getSteamApiKey()),
                    'keys' => [
                        'secret' => self::getSteamApiKey(),
                    ],
                ],
            ],
        ];
    }
}

// Автоматическая инициализация при подключении файла
Config::init();