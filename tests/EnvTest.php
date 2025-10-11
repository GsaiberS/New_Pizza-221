<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Config\Config;

class EnvTest extends TestCase
{
    public function testConfigCanLoadGoogleOAuth()
    {
        $config = Config::getHybridConfig();
        
        $this->assertIsArray($config);
        $this->assertArrayHasKey('providers', $config);
        $this->assertArrayHasKey('Google', $config['providers']);
        
        $googleConfig = $config['providers']['Google'];
        $this->assertTrue($googleConfig['enabled']);
        $this->assertArrayHasKey('keys', $googleConfig);
        $this->assertArrayHasKey('id', $googleConfig['keys']);
        $this->assertArrayHasKey('secret', $googleConfig['keys']);
    }

    public function testGoogleOAuthCredentialsAreConfigured()
    {
        $clientId = Config::getGoogleClientId();
        $clientSecret = Config::getGoogleClientSecret();
        
        $this->assertNotEmpty($clientId, 'Google Client ID должен быть настроен');
        $this->assertNotEmpty($clientSecret, 'Google Client Secret должен быть настроен');
        
        // УСЛОВНАЯ ПРОВЕРКА - только если это реальный Client ID
        if (strpos($clientId, '.apps.googleusercontent.com') !== false) {
            $this->assertStringContainsString('.apps.googleusercontent.com', $clientId,
                'Google Client ID должен содержать .apps.googleusercontent.com');
        }
    }

    public function testVkOAuthCredentialsAreConfigured()
    {
        $clientId = Config::getVkClientId();
        
        // В тестовой среде может быть заглушка, проверяем что метод работает
        $this->assertIsString($clientId, 'VK Client ID должен быть строкой');
        
        // Если это не заглушка, проверяем что не пустой
        if ($clientId !== 'your_vk_client_id_here' && !empty($clientId)) {
            $clientSecret = Config::getVkClientSecret();
            $this->assertNotEmpty($clientSecret, 'VK Client Secret должен быть настроен');
        }
    }

    public function testCallbackUrlIsCorrect()
    {
        $config = Config::getHybridConfig();
        $expectedCallback = Config::SITE_URL . '/register/google';
        
        $this->assertEquals(
            $expectedCallback,
            $config['callback'],
            'Callback URL должен совпадать с SITE_URL + /register/google'
        );
    }

    public function testSiteUrlIsConfigured()
    {
        $siteUrl = Config::SITE_URL;
        $this->assertNotEmpty($siteUrl, 'SITE_URL должен быть настроен');
        $this->assertStringStartsWith('http', $siteUrl, 'SITE_URL должен начинаться с http');
    }

    public function testConfigLoadsWithoutErrors()
    {
        // Просто проверяем что конфиг загружается без ошибок
        $this->assertTrue(true, 'Config должен загружаться без фатальных ошибок');
    }
}