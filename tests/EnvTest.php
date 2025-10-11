<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Config\Config;

class EnvTest extends TestCase
{
    public function testEnvironmentVariablesAreLoaded()
    {
        // Проверяем, что переменные окружения загружены
        $this->assertArrayHasKey('GOOGLE_CLIENT_ID', $_ENV, 
            'GOOGLE_CLIENT_ID должен быть в окружении');
        $this->assertArrayHasKey('GOOGLE_CLIENT_SECRET', $_ENV, 
            'GOOGLE_CLIENT_SECRET должен быть в окружении');
        $this->assertArrayHasKey('SITE_URL', $_ENV, 
            'SITE_URL должен быть в окружении');
    }

    public function testEnvironmentVariablesHaveValues()
    {
        $this->assertNotEmpty($_ENV['GOOGLE_CLIENT_ID'] ?? '', 
            'GOOGLE_CLIENT_ID не должен быть пустым');
        $this->assertNotEmpty($_ENV['GOOGLE_CLIENT_SECRET'] ?? '', 
            'GOOGLE_CLIENT_SECRET не должен быть пустым');
        $this->assertNotEmpty($_ENV['SITE_URL'] ?? '', 
            'SITE_URL не должен быть пустым');
    }

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
        $isConfigured = Config::isGoogleOAuthConfigured();
        $this->assertIsBool($isConfigured, 
            'isGoogleOAuthConfigured должен возвращать boolean');
        
        if (!$isConfigured) {
            $config = Config::getHybridConfig();
            $clientId = $config['providers']['Google']['keys']['id'] ?? '';
            $clientSecret = $config['providers']['Google']['keys']['secret'] ?? '';
            
            $this->fail("Google OAuth не настроен. Client ID: '$clientId', Client Secret: '$clientSecret'");
        }
    }

    public function testCallbackUrlIsCorrect()
    {
        $config = Config::getHybridConfig();
        $expectedCallback = ($_ENV['SITE_URL'] ?? 'http://localhost') . '/register/google';
        
        $this->assertEquals($expectedCallback, $config['callback'],
            'Callback URL должен совпадать с SITE_URL + /register/google');
    }
}