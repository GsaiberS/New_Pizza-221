<?php
// load_env.php

function loadEnvironmentVariables()
{
    $envPath = __DIR__ . '/.env';
    
    if (!file_exists($envPath)) {
        error_log("❌ .env file not found at: " . $envPath);
        return false;
    }

    try {
        // Проверяем, что Dotenv загружен
        if (!class_exists('Dotenv\Dotenv')) {
            error_log("❌ Dotenv class not available");
            return false;
        }
        
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        
        // Проверяем обязательные переменные
        $dotenv->required(['GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET']);
        
        error_log("✅ .env file loaded successfully");
        return true;
        
    } catch (Exception $e) {
        error_log("❌ Error loading .env: " . $e->getMessage());
        return false;
    }
}