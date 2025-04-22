<?php 
namespace App\Views;

use App\Views\BaseTemplate;

class UserTemplate extends BaseTemplate
{
    /*
        Формирование страницы "Регистрация"
    */
    public static function getUserTemplate(): string {
        $template = parent::getTemplate();
        $title = 'Вход пользователя';
        $content = <<<CORUSEL
        <main class="row p-5 justify-content-center align-items-center min-vh-100">
            <div class="col-lg-5 col-md-7 bg-white border rounded shadow p-4 animate__animated animate__fadeIn">
                <h3 class="text-center mb-5" style="color: rgb(208,157,176);">Вход пользователя</h3>
        CORUSEL;
        $content .= self::getFormLogin();
        $content .= "</div></main>";

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }

    /* 
        Форма входа (логин, пароль)
    */
    public static function getFormLogin(): string {
        $html = <<<FORMA
                <form action="/login" method="POST" class="animate__animated animate__fadeInUp">
                    <!-- Логин -->
                    <div class="input-group mb-3 custom-input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text" name="username" class="form-control" placeholder="Логин (имя или email)" required>
                    </div>

                    <!-- Пароль -->
                    <div class="input-group mb-3 custom-input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Пароль" required>
                    </div>

                    <!-- Кнопка -->
                    <button type="submit" class="btn btn-custom w-100 mt-4" 
                            style="background-color: rgb(208,157,176); color: #ffffff; font-weight: bold;">
                        <i class="fas fa-sign-in-alt me-2"></i>Войти
                    </button>
                </form>
        FORMA;
        return $html;
    }

    /**
     * Форма редактирования профиля
     */
    public static function getProfileForm(array $userData = []): string {
        $template = parent::getTemplate();
        $title = 'Редактирование профиля';
    
        $username = htmlspecialchars($userData['username'] ?? '');
        $email = htmlspecialchars($userData['email'] ?? '');
        $address = htmlspecialchars($userData['address'] ?? '');
        $phone = htmlspecialchars($userData['phone'] ?? '');
        $avatar = htmlspecialchars($userData['avatar'] ?? '/assets/images/default-avatar.png'); // значение по умолчанию
    
        $content = <<<HTML
        <style>
            .custom-input-group {
                position: relative;
                display: flex;
                align-items: center;
                border: 2px solid rgb(208, 157, 176);
                border-radius: 8px;
                overflow: hidden;
                transition: border-color 0.3s ease;
            }
    
            .custom-input-group:focus-within {
                border-color: rgb(180, 120, 150);
            }
    
            .custom-input-group .input-group-text {
                padding: 0.75rem 1rem;
                background-color: transparent;
                border: none;
                color: rgb(208, 157, 176);
            }
    
            .custom-input-group .form-control {
                flex: 1;
                border: none;
                box-shadow: none;
                outline: none;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                color: #333;
            }
    
            .custom-input-group .form-control::placeholder {
                color: #aaa;
            }
    
            .btn-custom {
                background-color: rgb(208,157,176);
                color: #fff;
                font-weight: bold;
                transition: all 0.3s ease;
            }
    
            .btn-custom:hover {
                background-color: rgb(180,120,150);
            }
    
            .avatar-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 2rem;
            }
    
            .avatar-preview-form {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                margin-bottom: 1rem;
                transition: transform 0.3s ease;
            }
    
            .avatar-preview-form:hover {
                transform: scale(1.05);
            }
    
            .upload-btn {
                font-size: 0.9rem;
                padding: 0.4rem 1rem;
                border-radius: 20px;
                background-color: rgb(208,157,176);
                color: white;
                border: none;
                transition: all 0.3s ease;
                cursor: pointer;
            }
    
            .upload-btn:hover {
                background-color: rgb(180,120,150);
            }
    
            input[type="file"] {
                display: none;
            }
            .custom-input-group {
              
            display: flex;
            align-items: center; /* Выравнивание по центру по вертикали */
            border: 3px solid rgb(208, 157, 176);
            border-radius: 10px;
            overflow: hidden;
            transition: border-color 0.3s ease;
            width: 100%;
            height: 56px; /* Фиксированная высота */
        }

        .custom-input-group:focus-within {
            border-color: rgb(180, 120, 150);
        }

        .custom-input-group .input-group-text {
            display: flex;
            align-items: center; /* Выравнивание иконки по центру */
            justify-content: center;
            padding: 0 1rem;
            background-color: transparent;
            color: rgb(208, 157, 176);
            border-right: 3px solid rgb(208, 157, 176); /* Увеличенная разделительная линия */
            height: 100%; /* Полная высота контейнера */
            line-height: 1; /* Убираем лишние отступы */
        }

        .custom-input-group .form-control {
            border: none;
            outline: none;
            box-shadow: none;
            padding: 0 1rem; /* Убираем лишние отступы */
            font-size: 1rem;
            flex: 1;
            color: #333;
            height: 100%; /* Полная высота контейнера */
            line-height: 1.5; /* Улучшаем читаемость текста */
            margin: 0; /* Убираем возможные отступы */
        }

        .custom-input-group .form-control::placeholder {
            color: #aaa;
        }



        </style>
    
        <main class="row p-4 justify-content-center align-items-start">
            <div class="col-lg-6 col-md-8 bg-white border rounded shadow p-4 animate__animated animate__fadeIn">
                <h3 class="text-center mb-4" style="color: rgb(208,157,176);">Редактирование профиля</h3>
    
                <form action="/profile" method="POST" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">
    
                    <!-- Аватар -->
                    <div class="avatar-wrapper">
                        <img src="{$avatar}" alt="Аватар пользователя" class="avatar-preview-form">
                        <label class="upload-btn">
                            <i class="fas fa-camera me-2"></i> Загрузить новый
                            <input type="file" name="avatar" accept="image/*">
                        </label>
                    </div>
    
                    <!-- Имя пользователя -->
                    <div class="input-group mb-3 custom-input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user-edit text-muted"></i>
                        </span>
                        <input type="text" name="username" class="form-control" value="{$username}" placeholder="Имя пользователя" required>
                    </div>
    
                    <!-- Email -->
                    <div class="input-group mb-3 custom-input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" class="form-control" value="{$email}" placeholder="Email" required>
                    </div>
    
                    <!-- Адрес -->
                    <div class="input-group mb-3 custom-input-group">
                        <span class="input-group-text">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                        </span>
                        <input type="text" name="address" class="form-control" value="{$address}" placeholder="Адрес">
                    </div>
    
                    <!-- Телефон -->
                    <div class="input-group mb-3 custom-input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone text-muted"></i>
                        </span>
                        <input type="text" name="phone" class="form-control" value="{$phone}" placeholder="Телефон">
                    </div>
    
                    <!-- Кнопка -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-custom">
                            <i class="fas fa-save me-2"></i> Сохранить изменения
                        </button>
                    </div>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const avatarInput = document.querySelector('input[name="avatar"]');
                        const avatarPreview = document.querySelector('.avatar-preview-form');

                        if (avatarInput && avatarPreview) {
                            avatarInput.addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        avatarPreview.src = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    // Если файл не выбран, возвращаем аватар по умолчанию
                                    avatarPreview.src = '/assets/images/default-avatar.png';
                                }
                            });
                        }
                    });
                </script>

            </div>
        </main>
        HTML;
    
       
    
        // Вставляем содержимое в базовый шаблон
        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}