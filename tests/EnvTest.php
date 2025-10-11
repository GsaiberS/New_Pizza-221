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
        $expectedCallback = ($_ENV['SITE_URL'] ?? 'http://localhost') . '/callback/google';
        
        $this->assertEquals(
            $expectedCallback,
            $config['callback'],
            'Callback URL должен совпадать с SITE_URL + /callback/google'
        );
    }
}