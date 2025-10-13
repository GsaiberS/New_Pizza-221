<?php 
namespace App\Services;

use PDO;

class UserDBStorage extends DBStorage implements ISaveStorage
{
    public function saveData(string $name, array $data): bool
    {
        $sql = "INSERT INTO `users`
        (`username`, `email`, `password`, `token`) 
        VALUES (:name, :email, :pass, :token)";

        $sth = $this->connection->prepare($sql);

        $result = $sth->execute([
            'name' => $data['username'],
            'email' => $data['email'],
            'pass' => $data['password'],
            'token' => $data['token']
        ]);

        return $result;
    }

    public function uniqueEmail(string $email): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT id FROM users WHERE email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->rowCount() === 0;
    }

    public function saveVerified($token): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT id FROM users WHERE token = ? AND is_verified = 0"
        );
        $stmt->execute([$token]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            $update = $this->connection->prepare(
                "UPDATE users SET is_verified = 1, token = '' WHERE id = ?"
            );
            $update->execute([$user['id']]);
            return true;
        }
        return false;
    }

    /**
     * Аутентификация пользователя
     */
    public function loginUser($username, $password): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT id, username, password FROM users 
            WHERE is_verified = 1 AND (username = ? OR email = ?)"
        );
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user === false || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        return true;
    }

    /**
    * Получение данных пользователя по ID
    */
    public function getUserById(int $userId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT id, username, email, address, phone, avatar, role, is_verified FROM users WHERE id = ?"
        );
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Если пользователь не найден, возвращаем пустой массив
        return $user ? $user : array();
    }

    /**
     * Обновление данных профиля пользователя, включая аватар
     */
    public function updateProfile(int $userId, array $data): bool
    {
        // Разрешенные для обновления поля. Включаем 'avatar'.
        $allowedFields = ['username', 'email', 'address', 'phone', 'avatar'];
        $setClauses = array();
        $executeData = array('id' => $userId);

        // Формируем динамический SET-блок для SQL
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $setClauses[] = "`{$key}` = :{$key}"; 
                $executeData[$key] = $value; // Собираем данные для выполнения
            }
        }

        if (empty($setClauses)) {
            // Если данных для обновления нет, возвращаем true
            return true; 
        }

        // Собираем финальный SQL-запрос
        $sql = "UPDATE `users` SET " . implode(', ', $setClauses) . " WHERE `id` = :id";

        $stmt = $this->connection->prepare($sql);
        
        // Выполняем запрос
        try {
            // Проверяем, удалось ли выполнить запрос
            return $stmt->execute($executeData);
        } catch (\PDOException $e) {
            // В случае ошибки SQL, записываем ее и возвращаем false
            error_log("Ошибка при обновлении профиля: " . $e->getMessage()); 
            return false;
        }
    }

    public function getDataHistory(int $userId)
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, created, all_sum, status FROM orders WHERE user_id = ? ORDER BY created DESC"
            );
            $stmt->execute([$userId]);
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($orders) ? $orders : null;
        } catch (\Exception $e) {
            error_log("Ошибка при получении истории заказов: " . $e->getMessage());
            return null;
        }
    }
    
    /**
    * Создание пользователя (для OAuth и обычной регистрации)
    */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO `users`
        (`username`, `email`, `password`, `token`, `is_verified`, `oauth_provider`, `oauth_id`, `avatar`) 
        VALUES (:username, :email, :password, :token, :is_verified, :oauth_provider, :oauth_id, :avatar)";

        $sth = $this->connection->prepare($sql);

        // Используем тернарные операторы вместо ??
        $password = isset($data['password']) ? $data['password'] : null;
        $token = isset($data['token']) ? $data['token'] : '';
        $is_verified = isset($data['is_verified']) ? $data['is_verified'] : 0;
        $oauth_provider = isset($data['oauth_provider']) ? $data['oauth_provider'] : null;
        $oauth_id = isset($data['oauth_id']) ? $data['oauth_id'] : null;
        $avatar = isset($data['avatar']) ? $data['avatar'] : null;

        $result = $sth->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $password,
            'token' => $token,
            'is_verified' => $is_verified,
            'oauth_provider' => $oauth_provider,
            'oauth_id' => $oauth_id,
            'avatar' => $avatar
        ]);

        return $result;
    }

    /**
    * Поиск пользователя по email
    */
    public function findByEmail(string $email)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE email = ?"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ? $user : null;
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = ?"
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ? $user : null;
    }

    public function getAllUsers(): array
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT * FROM users ORDER BY id ASC"
            );
            $stmt->execute();
            
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // ДОБАВИМ ДЕТАЛЬНУЮ ОТЛАДКУ
            error_log("=== DEBUG getAllUsers() ===");
            error_log("SQL: SELECT * FROM users ORDER BY id ASC");
            error_log("Found users: " . count($users));
            
            foreach ($users as $user) {
                $role = isset($user['role']) ? $user['role'] : 'NOT SET';
                error_log("User ID: {$user['id']}, Username: {$user['username']}, Role: {$role}, Email: {$user['email']}");
            }
            
            return $users ? $users : array();
        } catch (\Exception $e) {
            error_log("Ошибка при получении списка пользователей: " . $e->getMessage());
            return array();
        }
    }
}