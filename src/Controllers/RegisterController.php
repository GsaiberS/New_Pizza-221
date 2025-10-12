<?php
namespace App\Controllers;

use App\Views\RegisterTemplate;
use App\Services\UserFactory;
use App\Services\ValidateRegisterData;
use App\Services\Mailer;
use App\Config\Config;
use App\Services\UserDBStorage;
use Hybridauth\Hybridauth;

class RegisterController {

    public function get(): string {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            return $this->create();
        }
        return RegisterTemplate::getRegisterTemplate();
    }

    public function verify($token): string {
        if (!isset($token)) {
            $_SESSION['flash'] = "Ваш токен неверен";
            header("Location: /");
            return "";
        }

        if (Config::STORAGE_TYPE === Config::TYPE_DB) {
            $serviceDB = new UserDBStorage();
            if ($serviceDB->saveVerified($token)) {
                return RegisterTemplate::getVerifyTemplate();
            } else {
                $_SESSION['flash'] = "Ваш токен ненайден";
            }
        }
        header("Location: /");
        return "";
    }

    public function create(): string {
    $arr = [
        'username' => strip_tags($_POST['username'] ?? ''),
        'email' => strip_tags($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
    ];

    if (!ValidateRegisterData::validate($arr)) {
        header("Location: /register");
        exit; // Добавьте exit после header
    }

    $hashed_password = password_hash($arr['password'], PASSWORD_DEFAULT);
    $verification_token = bin2hex(random_bytes(16));

    $arr['password'] = $hashed_password;
    $arr['token'] = $verification_token;

    $model = UserFactory::createUser();
    
    try {
        $model->saveData($arr);
        
        Mailer::sendMailUserConfirmation(
            $arr['email'],
            $verification_token,
            $arr['username']
        );

        $_SESSION['flash'] = "Спасибо за регистрацию! На ваш email отправлено письмо для подтверждения регистрации.";
    } catch (\Exception $e) {
        $_SESSION['flash'] = "Ошибка при регистрации: " . $e->getMessage();
    }

    header("Location: /");
    exit; // Добавьте exit после header
}

    public function googleAuth(): void
    {
        try {
            // Проверяем, установлены ли необходимые зависимости
            if (!class_exists('Hybridauth\Hybridauth')) {
                throw new \Exception("Hybridauth library not found. Run: composer require hybridauth/hybridauth");
            }

            $config = Config::getHybridConfig();
            
            // Проверяем конфигурацию
            if (empty($config['providers']['Google']['keys']['id']) || 
                empty($config['providers']['Google']['keys']['secret'])) {
                throw new \Exception("Google OAuth credentials not configured");
            }

            $hybridauth = new Hybridauth($config);
            $adapter = $hybridauth->authenticate('Google');
            
            // Получаем профиль пользователя
            $userProfile = $adapter->getUserProfile();

            if (!$userProfile) {
                throw new \Exception("Failed to get user profile from Google");
            }

            $email = $userProfile->email ?? '';
            $name = $userProfile->displayName ?? 'Google User';
            $avatar = $userProfile->photoURL ?? '';

            if (empty($email)) {
                throw new \Exception("Google didn't provide email address");
            }

            $userStorage = new UserDBStorage();
            $user = $userStorage->findByEmail($email);

            if (!$user) {
                // Создаем нового пользователя
                $success = $userStorage->create([
                    'username' => $this->generateUsername($name, $email),
                    'email' => $email,
                    'avatar' => $avatar,
                    'password' => null, // OAuth users don't need password
                ]);
                
                if (!$success) {
                    throw new \Exception("Failed to create user account");
                }
                
                $user = $userStorage->findByEmail($email);
                if (!$user) {
                    throw new \Exception("Failed to retrieve created user");
                }
            }

            // Сохраняем данные в сессии
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['avatar'] = $user['avatar'] ?? '';
            $_SESSION['oauth_provider'] = 'google';

            $_SESSION['flash'] = "Добро пожаловать, {$user['username']}!";
            
            // Завершаем аутентификацию и очищаем данные Hybridauth
            $adapter->disconnect();
            
            header("Location: /");
            exit;

        } catch (\Exception $e) {
            error_log("Google OAuth error: " . $e->getMessage());
            $_SESSION['flash'] = "Ошибка входа через Google: " . $e->getMessage();
            header("Location: /login");
            exit;
        }
    }

    /**
     * Генерирует уникальное имя пользователя
     */
    private function generateUsername(string $name, string $email): string
    {
        $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
        if (empty($baseUsername)) {
            $baseUsername = explode('@', $email)[0];
        }
        
        // Добавляем случайный суффикс если имя слишком короткое
        if (strlen($baseUsername) < 3) {
            $baseUsername .= '_' . bin2hex(random_bytes(2));
        }
        
        return $baseUsername;
    }
    public function vkAuth(): void
{
    try {
        $vkService = new \App\Services\VKAuthService();

        // Шаг 1: Если нет кода авторизации - перенаправляем на VK
        if (!isset($_GET['code'])) {
            $authUrl = $vkService->getAuthUrl();
            header("Location: " . $authUrl);
            exit;
        }

        // Шаг 2: Проверяем state для защиты от CSRF
        if (isset($_GET['state']) && !$vkService->validateState($_GET['state'])) {
            throw new \Exception("Invalid state parameter");
        }

        // Шаг 3: Получаем access token
        $tokenData = $vkService->getAccessToken($_GET['code']);
        
        $accessToken = $tokenData['access_token'] ?? '';
        $userId = $tokenData['user_id'] ?? '';
        $email = $tokenData['email'] ?? '';

        if (empty($accessToken) || empty($userId)) {
            throw new \Exception("Failed to get access token from VK");
        }

        // Шаг 4: Получаем информацию о пользователе
        $userInfo = $vkService->getUserInfo($accessToken, $userId);

        $firstName = $userInfo['first_name'] ?? '';
        $lastName = $userInfo['last_name'] ?? '';
        $name = trim("{$firstName} {$lastName}");
        $avatar = $userInfo['photo_200'] ?? '';

        // Если email не получен (пользователь не разрешил), создаем временный
        if (empty($email)) {
            $email = "vk_{$userId}@temp.vk";
        }

        // Шаг 5: Работа с базой данных
        $userStorage = new UserDBStorage();
        $user = $userStorage->findByEmail($email);

        if (!$user) {
            // Создаем нового пользователя
            $success = $userStorage->create([
                'username' => $this->generateVkUsername($name, $firstName, $lastName, $userId),
                'email' => $email,
                'avatar' => $avatar,
                'password' => null,
                'is_verified' => 1,
                'oauth_provider' => 'vkontakte',
                'oauth_id' => $userId,
            ]);

            if (!$success) {
                throw new \Exception("Failed to create user account");
            }

            $user = $userStorage->findByEmail($email);
            if (!$user) {
                throw new \Exception("Failed to retrieve created user");
            }
        }

        // Шаг 6: Логиним пользователя
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['avatar'] = $user['avatar'] ?? '';
        $_SESSION['oauth_provider'] = 'vkontakte';

        $_SESSION['flash'] = "Добро пожаловать, {$user['username']}!";

        // Очищаем state
        unset($_SESSION['vk_oauth_state']);

        header("Location: /");
        exit;

    } catch (\Exception $e) {
        error_log("VK OAuth error: " . $e->getMessage());
        
        // Очищаем state в случае ошибки
        unset($_SESSION['vk_oauth_state']);
        
        $_SESSION['flash'] = "Ошибка входа через ВКонтакте: " . $e->getMessage();
        header("Location: /login");
        exit;
    }
}

/**
 * Генерирует username для VK пользователя
 */
private function generateVkUsername(string $fullName, string $firstName, string $lastName, string $userId): string
{
    // Пробуем использовать полное имя
    $username = preg_replace('/[^a-zA-Z0-9_]/', '', $fullName);
    
    // Если имя слишком короткое или пустое, используем комбинацию
    if (empty($username) || strlen($username) < 3) {
        $baseName = preg_replace('/[^a-zA-Z0-9_]/', '', $firstName . $lastName);
        if (empty($baseName)) {
            $baseName = 'vkuser';
        }
        $username = $baseName . '_' . substr($userId, -4);
    }
    
    // Ограничиваем длину
    if (strlen($username) > 20) {
        $username = substr($username, 0, 20);
    }
    
    return $username;
}
public function yandexAuth(): void
{
    try {
        $yandexService = new \App\Services\YandexAuthService();

        if (!isset($_GET['code'])) {
            $authUrl = $yandexService->getAuthUrl();
            header("Location: " . $authUrl);
            exit;
        }

        if (isset($_GET['state']) && !$yandexService->validateState($_GET['state'])) {
            throw new \Exception("Invalid state parameter");
        }

        $tokenData = $yandexService->getAccessToken($_GET['code']);
        $accessToken = $tokenData['access_token'] ?? '';

        if (empty($accessToken)) {
            throw new \Exception("Failed to get access token from Yandex");
        }

         $userInfo = $yandexService->getUserInfo($accessToken);

        $email = $userInfo['default_email'] ?? '';
        
        // ОБРАБОТКА АВАТАРКИ КАК В ПРОФИЛЕ
        $avatar = '';
        $avatarId = $userInfo['default_avatar_id'] ?? '';
        
        if (!empty($avatarId)) {
            $avatarUrl = "https://avatars.yandex.net/get-yapic/{$avatarId}/islands-200";
            
            // Скачиваем аватарку как в методе updateProfile()
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $avatarContent = file_get_contents($avatarUrl);
            if ($avatarContent !== false) {
                $newFileName = uniqid('yandex_avatar_', true) . '.jpg';
                $destPath = $uploadDir . $newFileName;
                
                if (file_put_contents($destPath, $avatarContent)) {
                    $avatar = "/assets/uploads/" . $newFileName;
                }
            }
        }

        // ФОРМИРУЕМ ФИО ИЗ ДАННЫХ ЯНДЕКСА
        $firstName = $userInfo['first_name'] ?? '';
        $lastName = $userInfo['last_name'] ?? '';
        $displayName = $userInfo['display_name'] ?? '';

        // Собираем полное имя
        if (!empty($firstName) && !empty($lastName)) {
            $name = $firstName . ' ' . $lastName;
        } elseif (!empty($displayName)) {
            $name = $displayName;
        } elseif (!empty($firstName)) {
            $name = $firstName;
        } elseif (!empty($lastName)) {
            $name = $lastName;
        } else {
            $name = explode('@', $email)[0] ?? 'Yandex_User';
        }

        if (empty($email)) {
            $email = "yandex_" . $userInfo['id'] . "@temp.yandex";
        }

        $userStorage = new UserDBStorage();
        $user = $userStorage->findByEmail($email);

        if (!$user) {
            $success = $userStorage->create([
                'username' => $name,
                'email' => $email,
                'avatar' => $avatar, // СОХРАНЯЕМ АВАТАРКУ
                'password' => null,
                'is_verified' => 1,
                'oauth_provider' => 'yandex',
                'oauth_id' => $userInfo['id'],
            ]);

            if (!$success) {
                throw new \Exception("Failed to create user account");
            }

            $user = $userStorage->findByEmail($email);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['avatar'] = $user['avatar'] ?? $avatar; // ИСПОЛЬЗУЕМ АВАТАРКУ
        $_SESSION['oauth_provider'] = 'yandex';

        $_SESSION['flash'] = "Добро пожаловать, {$user['username']}!";
        unset($_SESSION['yandex_oauth_state']);

        header("Location: /");
        exit;

    } catch (\Exception $e) {
        error_log("Yandex OAuth error: " . $e->getMessage());
        unset($_SESSION['yandex_oauth_state']);
        $_SESSION['flash'] = "Ошибка входа через Яндекс: " . $e->getMessage();
        header("Location: /login");
        exit;
    }
}

private function generateYandexUsername(string $name, string $userId): string
{
    $username = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
    if (empty($username) || strlen($username) < 3) {
        $username = 'yandex_' . substr($userId, -4);
    }
    if (strlen($username) > 20) {
        $username = substr($username, 0, 20);
    }
    return $username;
}
public function steamAuth(): void
{
    try {
        if (!class_exists('Hybridauth\Hybridauth')) {
            throw new \Exception("Hybridauth library not found");
        }

        $config = Config::getSteamConfig();
        $hybridauth = new Hybridauth($config);
        
        $adapter = $hybridauth->authenticate('Steam');
        $userProfile = $adapter->getUserProfile();

        if (!$userProfile) {
            throw new \Exception("Failed to get user profile from Steam");
        }

        $email = "steam_{$userProfile->identifier}@steam.com";
        $username = $userProfile->displayName ?? 'Steam_User';
        $avatar = $userProfile->photoURL ?? '';

        $userStorage = new UserDBStorage();
        $user = $userStorage->findByEmail($email);

        if (!$user) {
            $success = $userStorage->create([
                'username' => $username,
                'email' => $email,
                'avatar' => $avatar,
                'password' => null,
                'is_verified' => 1,
                'oauth_provider' => 'steam',
                'oauth_id' => $userProfile->identifier,
            ]);

            if (!$success) {
                throw new \Exception("Failed to create user account");
            }

            $user = $userStorage->findByEmail($email);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['avatar'] = $user['avatar'] ?? $avatar;
        $_SESSION['oauth_provider'] = 'steam';

        $_SESSION['flash'] = "Добро пожаловать, {$user['username']}!";
        
        $adapter->disconnect();
        header("Location: /");
        exit;

    } catch (\Exception $e) {
        error_log("Steam OAuth error: " . $e->getMessage());
        $_SESSION['flash'] = "Ошибка входа через Steam: " . $e->getMessage();
        header("Location: /login");
        exit;
    }
}
}