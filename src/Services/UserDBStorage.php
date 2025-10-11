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
    /**
 * Получение данных пользователя по ID
 */
    public function getUserById(int $userId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT id, username, email, address, phone, avatar FROM users WHERE id = ?"
        );
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Если пользователь не найден, возвращаем пустой массив вместо false
        return $user ?: [];
    }

    /**
     * Обновление данных профиля пользователя (с учётом аватара)
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $fields = [
            'username = :username',
            'email = :email',
            'address = :address',
            'phone = :phone'
        ];
        if (!empty($data['avatar'])) {
            $fields[] = 'avatar = :avatar';
        }

        $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->connection->prepare($query);

        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':address' => $data['address'] ?? null,
            ':phone' => $data['phone'] ?? null,
            ':id' => $userId
        ];

        if (!empty($data['avatar'])) {
            $params[':avatar'] = $data['avatar'];
        }

        return $stmt->execute($params);
    }
    public function getDataHistory(int $userId): ?array {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, created, all_sum, status FROM orders WHERE user_id = ? ORDER BY created DESC"
            );
            $stmt->execute([$userId]);
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($orders) ? $orders : null;
        } catch (\Exception $e) {
            // Можно добавить логгирование ошибки
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

    $result = $sth->execute([
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => $data['password'] ?? null,
        'token' => $data['token'] ?? '',
        'is_verified' => $data['is_verified'] ?? 0,
        'oauth_provider' => $data['oauth_provider'] ?? null,
        'oauth_id' => $data['oauth_id'] ?? null,
        'avatar' => $data['avatar'] ?? null
    ]);

    return $result;
}
/**
 * Поиск пользователя по email
 */
public function findByEmail(string $email): ?array
{
    $stmt = $this->connection->prepare(
        "SELECT * FROM users WHERE email = ?"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $user ?: null;
}
}

