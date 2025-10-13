<?php
namespace App\Controllers;

use App\Views\AdminTemplate;
use App\Services\UserDBStorage;

class AdminController
{
    public function index(): string
    {
        $storage = new UserDBStorage();
        $users = $storage->getAllUsers();

        $stats = [
            'users' => count($users),
            'verified' => count(array_filter($users, fn($u) => $u['is_verified'])),
            'admins' => count(array_filter($users, fn($u) => $u['role'] === 'admin')),
        ];

        return AdminTemplate::renderDashboard($users, $stats);
    }

    public function editUser(): string
{
    $userId = $_GET['id'] ?? null;
    if (!$userId) {
        die('ID пользователя не передан');
    }

    $userStorage = new UserDBStorage();
    $user = $userStorage->getUserById((int)$userId);

    if (!$user) {
        die('Пользователь не найден');
    }

    // Буферизация вывода
    ob_start();
    include_once __DIR__ . '/../Views/Admin/edit_user.php';
    return ob_get_clean();
}

    public function updateUser()
{
    $id = $_POST['id'] ?? null;
    if (!$id) {
        die("ID пользователя не найден");
    }

    $data = [
        'username' => $_POST['username'] ?? '',
        'email'    => $_POST['email'] ?? '',
        'address'  => $_POST['address'] ?? '',
        'phone'    => $_POST['phone'] ?? '',
        'role'     => $_POST['role'] ?? 'user',
        'is_verified' => $_POST['is_verified'] ?? 0,
        'token'    => $_POST['token'] ?? '',
        'password' => $_POST['password'] ?? null
    ];

    // Обработка аватара
    if (!empty($_FILES['avatar']['tmp_name'])) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = basename($_FILES['avatar']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
            $data['avatar'] = '/uploads/' . $fileName;
        }
    }

    $userStorage = new UserDBStorage();
    $userStorage->updateProfile((int)$id, $data);

    header('Location: /admin');
    exit;
}

    public function delete(): void
    {
        if (isset($_GET['id'])) {
            $storage = new UserDBStorage();
            $storage->deleteUser((int)$_GET['id']);
        }
        header('Location: /admin');
        exit;
    }
}
