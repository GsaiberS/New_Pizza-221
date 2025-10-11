<?php
namespace App\Services;

use App\Config\Config;

class YandexAuthService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    
    public function __construct()
    {
        $this->clientId = $_ENV['YANDEX_CLIENT_ID'] ?? '';
        $this->clientSecret = $_ENV['YANDEX_CLIENT_SECRET'] ?? '';
        $this->redirectUri = Config::SITE_URL . '/register/yandex';
        
        error_log("Yandex ClientID: " . $this->clientId);
        error_log("Yandex ClientSecret: " . substr($this->clientSecret, 0, 5) . '...');
    }

    /**
     * Генерирует URL для авторизации через Яндекс
     */
    public function getAuthUrl(): string
{
    $params = [
        'client_id' => $this->clientId,
        'redirect_uri' => $this->redirectUri,
        'response_type' => 'code',
        'scope' => 'login:email login:info',
        'state' => $this->generateState(),
        'force_confirm' => '1' // ДОБАВЬ ЭТУ СТРОКУ
    ];

    return 'https://oauth.yandex.ru/authorize?' . http_build_query($params);
}

    /**
     * Получает access token по коду авторизации
     */
    public function getAccessToken(string $code): array
{
    $url = 'https://oauth.yandex.ru/token';
    
    $postData = [
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
        'code' => $code,
        'grant_type' => 'authorization_code'
    ];

    error_log("=== YANDEX OAUTH DEBUG ===");
    error_log("ClientID: " . $this->clientId);
    error_log("ClientSecret: " . $this->clientSecret);
    error_log("Code: " . $code);
    error_log("Redirect URI: " . $this->redirectUri);
    error_log("Full POST Data: " . print_r($postData, true));

    $response = $this->httpPostRequest($url, $postData);
    
    error_log("Yandex Response: " . $response);
    
    $data = json_decode($response, true);
    
    if (isset($data['error'])) {
        error_log("Yandex Error Details: " . print_r($data, true));
        throw new \Exception('Yandex OAuth Error: ' . $data['error_description']);
    }

    return $data;
}

    /**
     * Получает информацию о пользователе
     */
    public function getUserInfo(string $accessToken): array
    {
        $url = 'https://login.yandex.ru/info?format=json';
        
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: OAuth {$accessToken}\r\n",
                'ignore_errors' => true,
                'timeout' => 10
            ]
        ]);

        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new \Exception('Failed to get user info from Yandex');
        }

        $data = json_decode($response, true);
        
        if (!isset($data['id'])) {
            throw new \Exception('Invalid user data from Yandex');
        }

        return $data;
    }

    /**
     * Валидирует state для защиты от CSRF
     */
    public function validateState(string $state): bool
    {
        if (!isset($_SESSION['yandex_oauth_state'])) {
            return false;
        }
        return hash_equals($_SESSION['yandex_oauth_state'], $state);
    }

    /**
     * Генерирует и сохраняет state
     */
    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['yandex_oauth_state'] = $state;
        return $state;
    }

    /**
     * Выполняет POST HTTP запрос
     */
    private function httpPostRequest(string $url, array $data): string
    {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'ignore_errors' => true,
                'timeout' => 10
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            throw new \Exception('Failed to make request to Yandex API: ' . ($error['message'] ?? 'Unknown error'));
        }

        return $response;
    }
}