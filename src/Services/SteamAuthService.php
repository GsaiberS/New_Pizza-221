<?php
namespace App\Services;

use App\Config\Config;

class SteamAuthService
{
    private $apiKey;
    private $redirectUri;

    public function __construct()
    {
        $this->apiKey = $_ENV['STEAM_API_KEY'] ?? '';
        $this->redirectUri = Config::SITE_URL . '/register/steam';
    }

    /**
     * Генерирует URL для авторизации через Steam (упрощенная версия)
     */
    public function getAuthUrl(): string
    {
        $returnTo = urlencode($this->redirectUri);
        $realm = urlencode(Config::SITE_URL);
        
        return "https://steamcommunity.com/openid/login?" . 
               "openid.ns=http://specs.openid.net/auth/2.0&" .
               "openid.mode=checkid_setup&" .
               "openid.return_to={$returnTo}&" .
               "openid.realm={$realm}&" .
               "openid.identity=http://specs.openid.net/auth/2.0/identifier_select&" .
               "openid.claimed_id=http://specs.openid.net/auth/2.0/identifier_select";
    }

    /**
     * Валидирует Steam OpenID ответ
     */
    public function validateLogin(): ?string
    {
        if (!isset($_GET['openid_claimed_id'])) {
            return null;
        }

        // Упрощенная валидация - просто извлекаем SteamID
        $claimedId = $_GET['openid_claimed_id'];
        preg_match('/^https?:\/\/steamcommunity\.com\/openid\/id\/(\d+)$/', $claimedId, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Получает информацию о пользователе Steam
     */
    public function getUserInfo(string $steamId): ?array
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_steam_api_key_here') {
            // Для тестирования без API ключа
            return [
                'steamid' => $steamId,
                'personaname' => 'Steam_User_' . substr($steamId, -4),
                'avatarfull' => '',
                'profileurl' => "https://steamcommunity.com/profiles/{$steamId}"
            ];
        }

        $url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$this->apiKey}&steamids={$steamId}";
        
        $response = @file_get_contents($url);
        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['response']['players'][0] ?? null;
    }
}