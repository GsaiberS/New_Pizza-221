<?php 
namespace App\Controllers;

use App\Views\UserTemplate;
use App\Config\Config;
use App\Services\UserDBStorage;

class UserController {
    private UserDBStorage $userStorage;

    public function __construct() {
        error_log("=== UserController constructed ===");
        if (Config::STORAGE_TYPE === Config::TYPE_DB) {
            $this->userStorage = new UserDBStorage();
        }
    }

    public function get(): string {
        error_log("=== UserController::get() called - showing login form ===");
        return UserTemplate::getUserTemplate();
    }

    public function login(): void
{
    error_log("=== UserController::login() called ===");
    error_log("POST data: " . print_r($_POST, true));
    
    $username = strip_tags($_POST['username'] ?? '');
    $password = strip_tags($_POST['password'] ?? '');

    // Проверяем логин
    $loginSuccess = $this->userStorage->loginUser($username, $password);
    if (!$loginSuccess) {
        $_SESSION['flash'] = "Ошибка ввода логина или пароля";
        header("Location: /login");
        exit();
    }

    // 🔥 После успешного логина — получаем пользователя из базы
    $user = $this->userStorage->findByUsername($username);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['avatar'] = $user['avatar'] ?? '/assets/image/default-avatar.png';
        $_SESSION['role'] = $user['role'] ?? 'user'; // ✅ добавлено
    }

    $_SESSION['flash'] = "Добро пожаловать, {$user['username']}!";
    header("Location: /");
    exit();
}


    public function profile(): string {
        error_log("=== UserController::profile() called - showing profile form ===");
        error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = "Необходимо войти в аккаунт.";
            header("Location: /login");
            exit();
        }

        $userData = $this->userStorage->getUserById((int)$_SESSION['user_id']);
        error_log("User data from DB: " . print_r($userData, true));

        return UserTemplate::getProfileForm($userData);
    }

    public function updateProfile(): void {
        error_log("=== UserController::updateProfile() CALLED ===");
        error_log("REQUEST METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));

        if (!isset($_SESSION['user_id'])) {
            error_log("ERROR: No user_id in session");
            $_SESSION['flash'] = "Необходимо войти в аккаунт.";
            header("Location: /login");
            exit();
        }

        $userId = (int)$_SESSION['user_id'];
        error_log("Updating profile for user_id: " . $userId);
        
        // Валидация данных
        $username = trim(strip_tags($_POST['username'] ?? ''));
        $email = trim(strip_tags($_POST['email'] ?? ''));
        $address = trim(strip_tags($_POST['address'] ?? ''));
        $phone = trim(strip_tags($_POST['phone'] ?? ''));

        error_log("Cleaned data - username: '$username', email: '$email', address: '$address', phone: '$phone'");

        // Проверка обязательных полей
        if (empty($username) || empty($email)) {
            error_log("ERROR: Empty username or email");
            $_SESSION['flash'] = "Имя пользователя и email обязательны для заполнения.";
            header("Location: /profile");
            exit();
        }

        // Проверка email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("ERROR: Invalid email format: " . $email);
            $_SESSION['flash'] = "Введите корректный email адрес.";
            header("Location: /profile");
            exit();
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'address' => $address,
            'phone' => $phone
        ];

        error_log("Prepared data for update: " . print_r($data, true));

        // Обработка аватарки
        if (
            isset($_FILES['avatar']) &&
            $_FILES['avatar']['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($_FILES['avatar']['tmp_name'])
        ) {
            error_log("Avatar file detected, processing...");
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileSize = $_FILES['avatar']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            error_log("File info - name: $fileName, size: $fileSize, extension: $fileExtension");

            // Проверка типа файла
            if (!in_array($fileExtension, $allowedExtensions)) {
                error_log("ERROR: Invalid file extension: " . $fileExtension);
                $_SESSION['flash'] = "Недопустимый формат файла. Разрешены: JPG, JPEG, PNG, GIF, WEBP.";
                header("Location: /profile");
                exit();
            }

            // Проверка размера файла (максимум 5MB)
            if ($fileSize > 5 * 1024 * 1024) {
                error_log("ERROR: File too large: " . $fileSize);
                $_SESSION['flash'] = "Файл слишком большой. Максимальный размер: 5MB.";
                header("Location: /profile");
                exit();
            }

            // Проверка MIME типа
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fileTmpPath);
            finfo_close($finfo);
            
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            error_log("Detected MIME type: " . $mime);
            
            if (!in_array($mime, $allowedMimeTypes)) {
                error_log("ERROR: Invalid MIME type: " . $mime);
                $_SESSION['flash'] = "Недопустимый тип файла.";
                header("Location: /profile");
                exit();
            }

            // Создание уникального имени файла
            $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/';
            $destPath = $uploadDir . $newFileName;

            error_log("Upload dir: " . $uploadDir);
            error_log("Destination path: " . $destPath);

            // Создание директории если не существует
            if (!is_dir($uploadDir)) {
                error_log("Creating upload directory: " . $uploadDir);
                if (!mkdir($uploadDir, 0755, true)) {
                    error_log("ERROR: Failed to create directory");
                    $_SESSION['flash'] = "Ошибка создания директории для загрузки.";
                    header("Location: /profile");
                    exit();
                }
            }

            // Перемещение файла
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                error_log("SUCCESS: File moved to: " . $destPath);
                $data['avatar'] = "/assets/uploads/" . $newFileName;
                
                // Удаление старого аватара если он существует
                $oldUserData = $this->userStorage->getUserById($userId);
                if (!empty($oldUserData['avatar']) && 
                    $oldUserData['avatar'] !== '/assets/image/default-avatar.png' &&
                    file_exists($_SERVER['DOCUMENT_ROOT'] . $oldUserData['avatar'])) {
                    error_log("Removing old avatar: " . $oldUserData['avatar']);
                    unlink($_SERVER['DOCUMENT_ROOT'] . $oldUserData['avatar']);
                }
            } else {
                error_log("ERROR: Failed to move uploaded file");
                $_SESSION['flash'] = "Ошибка при сохранении аватара.";
                header("Location: /profile");
                exit();
            }
        } else {
            error_log("No avatar file or upload error: " . ($_FILES['avatar']['error'] ?? 'NO FILE'));
        }

        error_log("Calling userStorage->updateProfile with data: " . print_r($data, true));
        
        // Обновление профиля
        if ($this->userStorage->updateProfile($userId, $data)) {
            error_log("SUCCESS: Profile updated successfully");
            $_SESSION['flash'] = "✅ Профиль успешно обновлен!";
            
            // Обновляем данные в сессии
            $_SESSION['username'] = $data['username'];
            if (isset($data['avatar'])) {
                $_SESSION['avatar'] = $data['avatar'];
            }
        } else {
            error_log("ERROR: Failed to update profile in database");
            $_SESSION['flash'] = "❌ Ошибка при обновлении профиля.";
        }

        error_log("Redirecting to /profile");
        header("Location: /profile");
        exit();
    }

    public function getOrdersHistory(): string {
        error_log("=== UserController::getOrdersHistory() called ===");
        global $user_id;

        $data = null;

        if (Config::STORAGE_TYPE === Config::TYPE_DB) {
            $serviceDB = new UserDBStorage();
            $data = $serviceDB->getDataHistory($user_id);
            if (!$data) {
                $_SESSION['flash'] = "Не удалось получить заказы.";
            }
        }

        return UserTemplate::getHistoryTemplate($data);
    }
}