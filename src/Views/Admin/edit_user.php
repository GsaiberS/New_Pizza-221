<?php 
use App\Views\BaseTemplate;
include_once __DIR__ . '/../../Views/BaseTemplate.php'; 
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать пользователя</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
        }
        .neon-border {
            border: 2px solid #0ff;
            box-shadow: 0 0 10px #0ff;
        }
        .form-label {
            color: #0ff;
        }
        .form-control, .form-select {
            transition: 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0ff;
            box-shadow: 0 0 5px #0ff;
        }
        .neon-glow {
            text-shadow: 0 0 5px #0ff, 0 0 10px #0ff;
        }
    </style>
</head>
<body class="bg-dark text-light">

<div class="container mt-5 mb-5">
    <div class="card bg-dark neon-border">
        <div class="card-body">
            <h2 class="text-info neon-glow mb-4"><i class="fas fa-user-edit me-2"></i>Редактировать пользователя</h2>

            <form method="post" action="/admin/update" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> Имя пользователя</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control bg-dark text-light border-info" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control bg-dark text-light border-info" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Пароль (оставьте пустым, если не хотите менять)</label>
                    <input type="password" name="password" class="form-control bg-dark text-light border-info">
                </div>

                <?php $isVerified = $user['is_verified'] ?? 0; ?>
                    <select name="is_verified" class="form-select bg-dark text-light border-info">
                        <option value="0" <?= $isVerified == 0 ? 'selected' : '' ?>>Нет</option>
                        <option value="1" <?= $isVerified == 1 ? 'selected' : '' ?>>Да</option>
                    </select>


                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-key"></i> Token</label>
                    <input type="text" name="token" value="<?= htmlspecialchars($user['token'] ?? '') ?>" class="form-control bg-dark text-light border-info">

                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-image"></i> Аватар</label>
                    <?php if (!empty($user['avatar'])): ?>
                        <div class="mb-2">
                            <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Аватар" width="100" class="rounded border border-info">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="avatar" class="form-control bg-dark text-light border-info">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-map-marker-alt"></i> Адрес</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" class="form-control bg-dark text-light border-info">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-phone"></i> Телефон</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="form-control bg-dark text-light border-info">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user-tag"></i> Роль</label>
                    <select name="role" class="form-select bg-dark text-light border-info">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Пользователь</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Сохранить</button>
                    <a href="/admin" class="btn btn-secondary"><i class="fas fa-times me-1"></i>Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
