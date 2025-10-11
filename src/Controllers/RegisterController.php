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
}