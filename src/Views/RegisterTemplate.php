<?php

namespace App\Views;
use App\Views\BaseTemplate;

class RegisterTemplate extends BaseTemplate
{
    
    public static function getRegisterTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Регистрация';

        // Основной контент страницы
        $content = <<<HTML
        <!-- Фоновая секция -->
        <div class="register-page d-flex align-items-center justify-content-center vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-size: cover; background-position: center; background-repeat: no-repeat; position: relative; overflow: hidden;">
            <!-- Анимированные элементы фона -->
            <div class="bg-animation">
                <div class="bg-bubble bubble-1"></div>
                <div class="bg-bubble bubble-2"></div>
                <div class="bg-bubble bubble-3"></div>
                <div class="bg-bubble bubble-4"></div>
                <div class="bg-bubble bubble-5"></div>
            </div>
            
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-5 col-md-7 col-sm-9">
                        <!-- Карточка регистрации -->
                        <div class="card shadow-lg p-5 glass-card" style="border-radius: 20px; backdrop-filter: blur(10px);">
                            <div class="text-center mb-4">
                                <div class="logo-container mb-3">
                                    <div class="logo-circle animate-float">
                                        <i class="fas fa-pizza-slice" style="color: rgb(208,157,176); font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <h1 class="display-5 fw-bold text-white mb-2">Регистрация</h1>
                                <p class="text-light opacity-75">Создайте учетную запись для доступа к платформе</p>
                            </div>

                            <!-- Форма регистрации -->
                            <form action="/register" method="POST" class="form-animated">
                                <!-- Имя пользователя -->
                                <div class="mb-4 input-group input-animate" data-delay="0.1">
                                    <span class="input-group-text bg-transparent border-end-0 text-white" style="border-radius: 10px 0 0 10px; border-color: rgba(255,255,255,0.3);">
                                        <i class="fas fa-user" style="color: rgb(208,157,176);"></i>
                                    </span>
                                    <input type="text" name="username" class="form-control border-start-0 bg-transparent text-white" placeholder="Имя пользователя" required style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.3); color: white;">
                                    <div class="input-focus-line"></div>
                                </div>

                                <!-- Email -->
                                <div class="mb-4 input-group input-animate" data-delay="0.2">
                                    <span class="input-group-text bg-transparent border-end-0 text-white" style="border-radius: 10px 0 0 10px; border-color: rgba(255,255,255,0.3);">
                                        <i class="fas fa-envelope" style="color: rgb(208,157,176);"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control border-start-0 bg-transparent text-white" placeholder="Email" required style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.3); color: white;">
                                    <div class="input-focus-line"></div>
                                </div>

                                <!-- Пароль -->
                                <div class="mb-4 input-group input-animate" data-delay="0.3">
                                    <span class="input-group-text bg-transparent border-end-0 text-white" style="border-radius: 10px 0 0 10px; border-color: rgba(255,255,255,0.3);">
                                        <i class="fas fa-lock" style="color: rgb(208,157,176);"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control border-start-0 bg-transparent text-white" placeholder="Пароль" required style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.3); color: white;">
                                    <div class="input-focus-line"></div>
                                </div>

                                <!-- Подтверждение пароля -->
                                <div class="mb-4 input-group input-animate" data-delay="0.4">
                                    <span class="input-group-text bg-transparent border-end-0 text-white" style="border-radius: 10px 0 0 10px; border-color: rgba(255,255,255,0.3);">
                                        <i class="fas fa-lock" style="color: rgb(208,157,176);"></i>
                                    </span>
                                    <input type="password" name="confirm_password" class="form-control border-start-0 bg-transparent text-white" placeholder="Подтвердите пароль" required style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.3); color: white;">
                                    <div class="input-focus-line"></div>
                                </div>

                                <!-- Кнопка регистрации -->
                                <button type="submit" class="btn w-100 mt-4 btn-glow animate-pop" data-delay="0.6" style="background: linear-gradient(135deg, rgb(208,157,176), #d1a7b9); color: white; border: none; border-radius: 12px; font-size: 1.1rem; padding: 12px; transition: all 0.5s ease;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Зарегистрироваться
                                </button>
                            </form>

                            <!-- Социальные кнопки -->
                            <div class="text-center mt-4">
                                <div class="divider text-light opacity-50 mb-3">или войти через</div>
                                <div class="social-buttons-container">
                                    <!-- Google -->
                                    <a href="/register/google" class="social-btn google-btn" title="Войти через Google">
                                        <div class="social-icon">
                                            <i class="fab fa-google"></i>
                                        </div>
                                        <span class="social-tooltip">Google</span>
                                    </a>
                                    
                                    <!-- VK -->
                                    <a href="/register/vk" class="social-btn vk-btn" title="Войти через ВКонтакте">
                                        <div class="social-icon">
                                            <i class="fab fa-vk"></i>
                                        </div>
                                        <span class="social-tooltip">ВКонтакте</span>
                                    </a>
                                    
                                    <!-- Steam -->
                                    <a href="/register/steam" class="social-btn steam-btn" title="Войти через Steam">
                                        <div class="social-icon">
                                            <i class="fab fa-steam"></i>
                                        </div>
                                        <span class="social-tooltip">Steam</span>
                                    </a>
                                    
                                    <!-- Яндекс -->
                                    <a href="/register/yandex" class="social-btn yandex-btn" title="Войти через Яндекс">
                                        <div class="social-icon">
                                            <i class="fab fa-yandex"></i>
                                        </div>
                                        <span class="social-tooltip">Яндекс</span>
                                    </a>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-light opacity-75">Уже есть аккаунт? <a href="/login" class="text-warning text-decoration-none fw-bold hover-glow">Войти</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Анимации и стили -->
        <style>
            /* Базовые стили */
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.1) !important;
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }

            /* Анимации фона */
            .bg-animation {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                overflow: hidden;
            }

            .bg-bubble {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                animation: float-bubble linear infinite;
            }

            .bubble-1 {
                width: 80px;
                height: 80px;
                top: 10%;
                left: 10%;
                animation-duration: 25s;
                animation-delay: 0s;
            }

            .bubble-2 {
                width: 120px;
                height: 120px;
                top: 60%;
                left: 80%;
                animation-duration: 30s;
                animation-delay: 5s;
            }

            .bubble-3 {
                width: 60px;
                height: 60px;
                top: 80%;
                left: 20%;
                animation-duration: 20s;
                animation-delay: 10s;
            }

            .bubble-4 {
                width: 100px;
                height: 100px;
                top: 30%;
                left: 85%;
                animation-duration: 35s;
                animation-delay: 15s;
            }

            .bubble-5 {
                width: 70px;
                height: 70px;
                top: 15%;
                left: 70%;
                animation-duration: 28s;
                animation-delay: 8s;
            }

            @keyframes float-bubble {
                0%, 100% {
                    transform: translateY(0) translateX(0) rotate(0deg);
                }
                25% {
                    transform: translateY(-20px) translateX(10px) rotate(5deg);
                }
                50% {
                    transform: translateY(10px) translateX(-15px) rotate(-5deg);
                }
                75% {
                    transform: translateY(-15px) translateX(-10px) rotate(3deg);
                }
            }

            /* Логотип и анимации */
            .logo-container {
                display: flex;
                justify-content: center;
            }

            .logo-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(10px);
                border: 2px solid rgba(255, 255, 255, 0.3);
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
            }

            /* Анимации формы */
            .form-animated .input-animate {
                opacity: 0;
                transform: translateY(20px);
                animation: slideInUp 0.8s ease forwards;
            }

            @keyframes slideInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-pop {
                opacity: 0;
                transform: scale(0.8);
                animation: popIn 0.6s ease forwards;
            }

            @keyframes popIn {
                0% {
                    opacity: 0;
                    transform: scale(0.8);
                }
                70% {
                    transform: scale(1.05);
                }
                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            /* Эффекты для инпутов */
            .input-group {
                position: relative;
            }

            .input-focus-line {
                position: absolute;
                bottom: 0;
                left: 50%;
                width: 0;
                height: 2px;
                background: linear-gradient(90deg, rgb(208,157,176), #d1a7b9);
                transition: all 0.4s ease;
                transform: translateX(-50%);
            }

            .input-group:focus-within .input-focus-line {
                width: 100%;
            }

            .form-control:focus {
                background: rgba(255, 255, 255, 0.1) !important;
                border-color: rgba(208,157,176, 0.5) !important;
                box-shadow: 0 0 0 0.2rem rgba(208,157,176, 0.25) !important;
                color: white !important;
            }

            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.6) !important;
            }

            /* Эффекты для кнопок */
            .btn-glow {
                position: relative;
                overflow: hidden;
            }

            .btn-glow::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s;
            }

            .btn-glow:hover::before {
                left: 100%;
            }

            .btn-glow:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(208,157,176, 0.4);
            }

            .hover-glow:hover {
                text-shadow: 0 0 10px rgba(255,255,255,0.8);
                transition: text-shadow 0.3s ease;
            }

            /* Разделитель */
            .divider {
                display: flex;
                align-items: center;
                text-align: center;
                margin: 20px 0;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid rgba(255,255,255,0.3);
            }

            .divider::before {
                margin-right: 10px;
            }

            .divider::after {
                margin-left: 10px;
            }

            /* Стили для социальных кнопок */
            .social-buttons-container {
                display: flex;
                justify-content: center;
                gap: 15px;
                flex-wrap: wrap;
                max-width: 100%;
            }

            .social-btn {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                width: 60px;
                margin: 0 5px;
                opacity: 0;
                animation: socialSlideIn 0.8s ease forwards;
            }

            .social-btn:nth-child(1) { animation-delay: 0.7s; }
            .social-btn:nth-child(2) { animation-delay: 0.8s; }
            .social-btn:nth-child(3) { animation-delay: 0.9s; }
            .social-btn:nth-child(4) { animation-delay: 1.0s; }

            @keyframes socialSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(15px) rotate(5deg);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) rotate(0deg);
                }
            }

            .social-icon {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.3rem;
                transition: all 0.4s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                position: relative;
                overflow: hidden;
            }

            .social-icon::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                transition: left 0.6s;
            }

            .social-btn:hover .social-icon::before {
                left: 100%;
            }

            .social-btn:hover .social-icon {
                transform: translateY(-5px) scale(1.1);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }

            .social-tooltip {
                position: absolute;
                bottom: -25px;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 0.7rem;
                white-space: nowrap;
                opacity: 0;
                transform: translateY(10px);
                transition: all 0.3s ease;
                pointer-events: none;
                z-index: 1000;
            }

            .social-btn:hover .social-tooltip {
                opacity: 1;
                transform: translateY(0);
            }

            /* Google */
            .google-btn .social-icon {
                background: linear-gradient(135deg, #db4437, #e57373);
                color: white;
            }

            .google-btn:hover .social-icon {
                background: linear-gradient(135deg, #c1351d, #db4437);
            }

            /* VK */
            .vk-btn .social-icon {
                background: linear-gradient(135deg, #4c75a3, #5a7fab);
                color: white;
            }

            .vk-btn:hover .social-icon {
                background: linear-gradient(135deg, #3a5f8c, #4c75a3);
            }

            /* Steam */
            .steam-btn .social-icon {
                background: linear-gradient(135deg, #171a21, #2a475e);
                color: white;
            }

            .steam-btn:hover .social-icon {
                background: linear-gradient(135deg, #0e1015, #171a21);
            }

            /* Яндекс */
            .yandex-btn .social-icon {
                background: linear-gradient(135deg, #FFCC00, #FFD700);
                color: #000;
            }

            .yandex-btn:hover .social-icon {
                background: linear-gradient(135deg, #e6b800, #FFCC00);
            }

            /* Адаптивность */
            @media (max-width: 768px) {
                .container {
                    padding: 0 15px;
                }
                
                .card {
                    padding: 30px 25px !important;
                }
                
                .social-icon {
                    width: 45px;
                    height: 45px;
                    font-size: 1.2rem;
                }
                
                .social-btn {
                    width: 50px;
                }
            }

            /* Гарантия круглой формы */
            .social-icon {
                border-radius: 50% !important;
            }

            .social-btn {
                flex-shrink: 0;
            }
        </style>

        <script>
            // Активация анимаций с задержкой
            document.addEventListener('DOMContentLoaded', function() {
                // Анимация инпутов с задержкой
                const inputs = document.querySelectorAll('.input-animate');
                inputs.forEach(input => {
                    const delay = input.getAttribute('data-delay') || 0;
                    input.style.animationDelay = delay + 's';
                });

                // Анимация кнопки с задержкой
                const button = document.querySelector('.animate-pop');
                if (button) {
                    const delay = button.getAttribute('data-delay') || 0;
                    button.style.animationDelay = delay + 's';
                }
            });
        </script>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }

    public static function getVerifyTemplate(): string {
        $template = parent::getTemplate();
        $title = 'Подтверждение нового пользователя';
        
        $content = <<<HTML
        <main class="row p-5 justify-content-center align-items-center min-vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="col-12 col-md-6 text-center text-white p-5">
                <div class="success-animation">
                    <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                </div>
                <h3 class="mb-4">Успешное завершение регистрации</h3>
                <p class="lead">Ваш email успешно подтвержден!</p>
                <p class="text-light opacity-75">Теперь вы можете войти на сайт.</p>
                <a href="/login" class="btn btn-success mt-4 px-4 py-2" style="border-radius: 10px; font-size: 1.1rem;">Войти</a>
            </div>
        </main>
        
        <style>
            .success-animation {
                animation: bounceIn 1s ease;
            }
            
            @keyframes bounceIn {
                0% {
                    opacity: 0;
                    transform: scale(0.3);
                }
                50% {
                    opacity: 1;
                    transform: scale(1.05);
                }
                70% {
                    transform: scale(0.9);
                }
                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }
        </style>
HTML;
    
        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}