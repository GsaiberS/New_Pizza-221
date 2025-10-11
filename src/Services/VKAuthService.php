<?php
namespace App\Services;

use App\Config\Config;

class VKAuthService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $apiVersion = '5.131';

    public function __construct()
    {
        // ТЕСТОВЫЙ ХАРДКОД
        $this->clientId = '54230789';
        $this->clientSecret = 'U0FiqdIIo9Gkc884ObDe';
        $this->redirectUri = 'http://localhost/register/vk';
        
        error_log("VKAuthService - ClientID: " . $this->clientId);
        error_log("VKAuthService - Redirect: " . $this->redirectUri);
    }

    /**
     * Генерирует URL для авторизации через VK
     */
    public function getAuthUrl(): string
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email', // Запрашиваем email
            'v' => $this->apiVersion,
            'state' => $this->generateState() // Защита от CSRF
        ];

        return 'https://oauth.vk.com/authorize?' . http_build_query($params);
    }

    /**
     * Получает access token по коду авторизации
     */
    public function getAccessToken(string $code): array
    {
        $url = 'https://oauth.vk.com/access_token?' . http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code
        ]);

        $response = $this->httpRequest($url);
        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('VK OAuth Error: ' . $data['error_description']);
        }

        return $data;
    }

    /**
     * Получает информацию о пользователе
     */
    public function getUserInfo(string $accessToken, string $userId): array
    {
        $url = 'https://api.vk.com/method/users.get?' . http_build_query([
            'user_ids' => $userId,
            'access_token' => $accessToken,
            'fields' => 'photo_200,first_name,last_name,screen_name',
            'v' => $this->apiVersion
        ]);

        $response = $this->httpRequest($url);
        $data = json_decode($response, true);

        if (!isset($data['response'][0])) {
            throw new \Exception('Failed to get user info from VK');
        }

        return $data['response'][0];
    }

    /**
     * Валидирует state для защиты от CSRF
     */
    public function validateState(string $state): bool
    {
        if (!isset($_SESSION['vk_oauth_state'])) {
            return false;
        }

        return hash_equals($_SESSION['vk_oauth_state'], $state);
    }

    /**
     * Генерирует и сохраняет state
     */
    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['vk_oauth_state'] = $state;
        return $state;
    }

    /**
     * Выполняет HTTP запрос
     */
    private function httpRequest(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'timeout' => 10
            ]
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new \Exception('Failed to make request to VK API');
        }

        return $response;
    }
}