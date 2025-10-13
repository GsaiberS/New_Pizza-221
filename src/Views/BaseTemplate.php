<?php
namespace App\Views;

class BaseTemplate
{
    public static function getTemplate()
    {
        global $user_id, $username, $avatar;

        $template = <<<HTML
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>%s</title>
            <link rel="icon" type="image/x-icon" href="/assets/image/BP.ico">
            <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                :root {
                    --primary-gradient: linear-gradient(135deg, #667eea, #764ba2);
                    --secondary-gradient: linear-gradient(135deg, #d09db0, #667eea);
                    --glass-bg: rgba(255, 255, 255, 0.95);
                    --glass-border: rgba(255, 255, 255, 0.3);
                }

                body {
                    font-family: 'Roboto', sans-serif !important;
                    font-size: 16px;
                    line-height: 1.6;
                    background: 
                        radial-gradient(circle at 20%% 80%%, rgba(208,157,176,0.15) 0%%, transparent 50%%),
                        radial-gradient(circle at 80%% 20%%, rgba(102,126,234,0.15) 0%%, transparent 50%%),
                        radial-gradient(circle at 40%% 40%%, rgba(118,75,162,0.1) 0%%, transparent 50%%),
                        linear-gradient(135deg, #667eea 0%%, #764ba2 50%%, #f8f9fa 100%%);
                    background-size: cover;
                    background-attachment: fixed;
                    color: #343a40;
                    position: relative;
                    overflow-x: hidden;
                    min-height: 100vh;
                }

                /* Анимированные пузырьки фона */
                .bg-animation {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%%;
                    height: 100%%;
                    z-index: -1;
                    overflow: hidden;
                }

                .bg-bubble {
                    position: absolute;
                    border-radius: 50%%;
                    background: rgba(255, 255, 255, 0.1);
                    animation: float-bubble linear infinite;
                    backdrop-filter: blur(5px);
                }

                .bubble-1 {
                    width: 120px;
                    height: 120px;
                    top: 10%%;
                    left: 5%%;
                    animation-duration: 25s;
                    background: radial-gradient(circle at 30%% 30%%, rgba(208,157,176,0.2), transparent 70%%);
                }

                .bubble-2 {
                    width: 80px;
                    height: 80px;
                    top: 70%%;
                    left: 85%%;
                    animation-duration: 30s;
                    animation-delay: 5s;
                    background: radial-gradient(circle at 30%% 30%%, rgba(102,126,234,0.2), transparent 70%%);
                }

                .bubble-3 {
                    width: 60px;
                    height: 60px;
                    top: 80%%;
                    left: 15%%;
                    animation-duration: 20s;
                    animation-delay: 10s;
                    background: radial-gradient(circle at 30%% 30%%, rgba(118,75,162,0.2), transparent 70%%);
                }

                .bubble-4 {
                    width: 100px;
                    height: 100px;
                    top: 25%%;
                    left: 90%%;
                    animation-duration: 35s;
                    animation-delay: 15s;
                    background: radial-gradient(circle at 30%% 30%%, rgba(208,157,176,0.15), transparent 70%%);
                }

                @keyframes float-bubble {
                    0%%, 100%% {
                        transform: translateY(0) translateX(0) rotate(0deg);
                    }
                    25%% {
                        transform: translateY(-15px) translateX(8px) rotate(3deg);
                    }
                    50%% {
                        transform: translateY(8px) translateX(-12px) rotate(-2deg);
                    }
                    75%% {
                        transform: translateY(-10px) translateX(-8px) rotate(2deg);
                    }
                }

                header {
                    margin-bottom: 2rem;
                }

                .navbar {
                    background: var(--glass-bg) !important;
                    backdrop-filter: blur(15px);
                    border-bottom: 1px solid var(--glass-border);
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                    /* !!! ДОБАВЛЕН z-index для navbar, чтобы он был поверх основного контента */
                    z-index: 1030; /* Стандартный z-index для navbar в Bootstrap обычно около 1030 */
                }

                .navbar-brand img {
                    margin-right: 10px;
                    transition: transform 0.3s ease;
                }

                .navbar-brand:hover img {
                    transform: scale(1.1) rotate(5deg);
                }

                .navbar-brand {
                    font-size: 1.5rem;
                    font-weight: bold;
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    transition: all 0.3s ease;
                }

                .navbar-nav .nav-link {
                    font-size: 1.1rem;
                    color: #343a40 !important;
                    margin-right: 1.5rem;
                    transition: all 0.3s ease;
                    position: relative;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    text-decoration: none !important;
                }

                /* ИКОНКИ ВКЛАДОК В ГРАДИЕНТЕ */
                .navbar-nav .nav-link i {
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    transition: all 0.3s ease;
                    font-size: 1.1rem;
                }

                .navbar-nav .nav-link::after {
                    content: '';
                    position: absolute;
                    bottom: -2px;
                    left: 0;
                    width: 0;
                    height: 2px;
                    background: linear-gradient(90deg, #667eea, #764ba2);
                    transition: width 0.3s ease;
                }

                .navbar-nav .nav-link:hover::after {
                    width: 100%%;
                }

                .navbar-nav .nav-link:hover {
                    color: #667eea !important;
                    transform: translateY(-1px);
                    text-decoration: none !important;
                }

                .navbar-nav .nav-link:hover i {
                    transform: scale(1.1);
                }

                /* Кнопка регистрации - СИНИЙ ГРАДИЕНТ */
                .btn-register {
                    background: var(--primary-gradient);
                    border: none;
                    color: #ffffff;
                    transition: all 0.3s ease;
                    border-radius: 25px;
                    padding: 10px 20px;
                    position: relative;
                    overflow: hidden;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    text-decoration: none !important;
                }

                .btn-register::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%%;
                    width: 100%%;
                    height: 100%%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                    transition: left 0.5s;
                }

                .btn-register:hover::before {
                    left: 100%%;
                }

                .btn-register:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(102,126,234, 0.4);
                    color: #ffffff;
                    text-decoration: none !important;
                }

                .btn-register i {
                    color: #ffffff;
                    transition: transform 0.3s ease;
                }

                .btn-register:hover i {
                    transform: scale(1.1);
                }

                /* Алерты */
                .alert-custom {
                    background: rgba(212, 237, 218, 0.9);
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(195, 230, 203, 0.5);
                    color: #155724;
                    border-radius: 12px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                    z-index: 1040; /* Увеличиваем z-index для алертов */
                }

                .alert-custom .btn-close {
                    color: #ffffff;
                    opacity: 0.8;
                }

                .alert-custom .btn-close:hover {
                    color: rgb(141, 34, 123);
                    opacity: 1;
                }

                /* Футер */
                footer {
                    text-align: center;
                    padding: 1rem 0;
                    background: linear-gradient(135deg, #2c3e50, #34495e);
                    color: #ffffff;
                    font-size: 0.9rem;
                    z-index: 0; /* Убедитесь, что футер ниже всего */
                }

                /* Выпадающее меню - ИСПРАВЛЕННОЕ */
                .dropdown-menu {
                    background: rgba(255, 255, 255, 0.98);
                    backdrop-filter: blur(20px);
                    border-radius: 16px;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    padding: 8px;
                    min-width: 280px !important; /* Фиксированная ширина */
                    z-index: 1050; /* Достаточно высокий z-index для обычных дропдаунов */
                }

                .dropdown-item {
                    color: #343a40;
                    transition: all 0.3s ease-in-out;
                    padding: 12px 16px;
                    border-radius: 12px;
                    margin: 2px 0;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    font-weight: 500;
                    text-decoration: none !important; /* Убираем подчеркивание */
                    width: 100%%;
                    box-sizing: border-box;
                }

                .dropdown-item i {
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    font-size: 1.1rem;
                    width: 20px;
                    text-align: center;
                    flex-shrink: 0;
                }

                .dropdown-item:hover {
                    background: var(--primary-gradient);
                    color: #ffffff !important;
                    transform: translateX(5px);
                    text-decoration: none !important;
                }

                .dropdown-item:hover i {
                    -webkit-text-fill-color: white;
                    color: white;
                }

                /* Анимация появления */
                .animate__fadeIn {
                    animation-duration: 0.3s;
                }

                .transition-arrow {
                    transform: rotate(0deg);
                    transition: transform 0.3s ease;
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                .transition-arrow.open {
                    transform: rotate(90deg);
                }

                @media (max-width: 768px) {
                    .transition-arrow {
                        display: none !important;
                    }
                    
                    .navbar-nav .nav-link {
                        margin-right: 0;
                        padding: 10px 15px;
                    }

                    .dropdown-menu {
                        min-width: 250px !important;
                        margin-right: 10px;
                    }
                }

                a.dropdown-toggle::after {
                    display: none !important;
                }

                /* Исправленное выпадающее меню пользователя */
                .user-dropdown-toggle {
                    border-radius: 30px;
                    transition: all 0.3s ease;
                    gap: 10px;
                    padding: 8px 16px;
                    border: 2px solid transparent;
                    text-decoration: none !important;
                    display: flex;
                    align-items: center;
                }

                .user-dropdown-toggle:hover {
                    border-color: rgba(102,126,234, 0.2);
                    transform: translateY(-1px);
                    text-decoration: none !important;
                }

                .avatar-preview {
                    width: 40px;
                    height: 40px;
                    border: 2px solid #fff;
                    transition: transform 0.3s ease;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    flex-shrink: 0;
                }

                .user-dropdown-toggle:hover .avatar-preview {
                    transform: scale(1.05);
                }

                .username-text {
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    font-weight: 600;
                    max-width: 150px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                /* ФИКСИРОВАННОЕ меню пользователя */
                .user-dropdown-menu {
                    border-radius: 16px;
                    border: none;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
                    margin-top: 12px !important;
                    background: rgba(255, 255, 255, 0.98);
                    backdrop-filter: blur(20px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    padding: 8px;
                    min-width: 280px !important;
                    position: absolute !important;
                    right: 0 !important;
                    left: auto !important;
                    /* !!! ЭТО ГЛАВНОЕ ИЗМЕНЕНИЕ: z-index для перекрытия основного контента !!! */
                    z-index: 1051 !important; /* Значение выше, чем у большинства элементов, включая модальные окна */
                }

                .user-dropdown-item {
                    display: flex;
                    align-items: center;
                    padding: 12px 16px;
                    border-radius: 12px;
                    transition: all 0.3s ease;
                    margin: 2px 0;
                    gap: 12px;
                    text-decoration: none !important;
                    width: 100%%;
                    box-sizing: border-box;
                }

                .user-dropdown-item .icon-wrapper {
                    width: 40px;
                    height: 40px;
                    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                    border-radius: 50%%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    flex-shrink: 0;
                }

                .user-dropdown-item .icon-wrapper i {
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    font-size: 1.1rem;
                }

                .user-dropdown-item:hover {
                    background: var(--primary-gradient);
                    transform: translateX(5px);
                    text-decoration: none !important;
                }

                .user-dropdown-item:hover .icon-wrapper {
                    background: rgba(255,255,255,0.9);
                    transform: scale(1.1);
                }

                .user-dropdown-item:hover .icon-wrapper i {
                    -webkit-text-fill-color: #667eea;
                }

                .user-dropdown-item .item-text {
                    flex: 1;
                    min-width: 0;
                }

                .user-dropdown-item .item-text span {
                    display: block;
                    font-weight: 600;
                    color: #343a40;
                    transition: color 0.3s ease;
                }

                .user-dropdown-item .item-text small {
                    display: block;
                    color: #6c757d;
                    font-size: 0.8rem;
                    transition: color 0.3s ease;
                }

                .user-dropdown-item:hover .item-text span {
                    color: white !important;
                }

                .user-dropdown-item:hover .item-text small {
                    color: rgba(255,255,255,0.8) !important;
                }

                .logout-item:hover .icon-wrapper i {
                    -webkit-text-fill-color: #dc3545 !important;
                }

                main.container {
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(15px);
                    border-radius: 20px;
                    padding: 2rem;
                    margin-bottom: 2rem;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    z-index: 1; /* Убедимся, что основной контент имеет низкий z-index */
                    position: relative; /* Необходимо для работы z-index */
                }

                /* Социальные иконки в футере */
                .social-icon-footer {
                    transition: all 0.3s ease;
                    color: #ffffff;
                    opacity: 0.8;
                    text-decoration: none !important;
                }

                .social-icon-footer:hover {
                    opacity: 1;
                    transform: translateY(-3px);
                    color: #667eea;
                    text-decoration: none !important;
                }

                /* Дополнительные исправления для позиционирования */
                .navbar-nav .dropdown {
                    position: relative;
                }

                .dropdown-menu-end {
                    right: 0 !important;
                    left: auto !important;
                }

                /* Фикс для мобильных устройств */
                @media (max-width: 991.98px) {
                    .user-dropdown-menu {
                        position: static !important;
                        min-width: 100%% !important;
                    }
                    
                    .navbar-collapse {
                        padding-bottom: 1rem;
                    }
                }
            </style>
            <script src="../../assets/css/bootstrap.bundle.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>
        </head>
        <body>
            <div class="bg-animation">
                <div class="bg-bubble bubble-1"></div>
                <div class="bg-bubble bubble-2"></div>
                <div class="bg-bubble bubble-3"></div>
                <div class="bg-bubble bubble-4"></div>
            </div>
            
            <header>
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="http://localhost/">
                            <img src="/assets/image/BP.ico" alt="Логотип компании" width="64" height="64">
                            BUBBLE PIZZA
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="http://localhost/">
                                        <i class="fas fa-home"></i>Главная
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://localhost/about">
                                        <i class="fas fa-info-circle"></i>О нас
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://localhost/products">
                                        <i class="fas fa-pizza-slice"></i>Каталог
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://localhost/order">
                                        <i class="fas fa-shopping-cart"></i>Заказ
                                    </a>
                                </li>
                            </ul>
HTML;

if ($user_id > 0) {
    $template .= <<<HTML
    <form action="/profile" method="POST" enctype="multipart/form-data">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center user-dropdown-toggle"
                   href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    <img src="{$avatar}" alt="Аватар пользователя" 
                         class="rounded-circle avatar-preview">
                    <span class="d-none d-md-inline username-text ms-2">{$username}</span>
                    <i class="fas fa-chevron-right transition-arrow ms-2" id="dropdownArrow"></i>
                </a>
                <ul class="dropdown-menu user-dropdown-menu dropdown-menu-end animate__animated animate__fadeIn"
                    aria-labelledby="navbarDropdown">
                    <li>
                        <a class="user-dropdown-item" href="http://localhost/profile">
                            <div class="icon-wrapper">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="item-text">
                                <span class="fw-semibold">Профиль</span>
                                <small class="text-muted">Настройки аккаунта</small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="user-dropdown-item" href="http://localhost/history">
                            <div class="icon-wrapper">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="item-text">
                                <span class="fw-semibold">Заказы</span>
                                <small class="text-muted">История заказов</small>
                            </div>
                        </a>
                    </li>
HTML;

    // 🔥 Добавляем кнопку "Админ-панель" только для администратора
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        $template .= <<<HTML
                    <li>
                        <a class="user-dropdown-item" href="http://localhost/admin">
                            <div class="icon-wrapper">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="item-text">
                                <span class="fw-semibold">Админ-панель</span>
                                <small class="text-muted">Управление сайтом</small>
                            </div>
                        </a>
                    </li>
        HTML;
    }

    $template .= <<<HTML
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <a class="user-dropdown-item logout-item" href="http://localhost/logout">
                            <div class="icon-wrapper">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div class="item-text">
                                <span class="fw-semibold">Выход</span>
                                <small class="text-muted">Завершить сеанс</small>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </form>
HTML;
}
 else {
    $template .= <<<HTML
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <button class="btn btn-register dropdown-toggle" id="registerDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-plus"></i>Вход
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="registerDropdown">
                                        <li><a class="dropdown-item" href="http://localhost/register">
                                            <i class="fas fa-user-plus"></i>Зарегистрироваться
                                        </a></li>
                                        <li><a class="dropdown-item" href="http://localhost/login">
                                            <i class="fas fa-sign-in-alt"></i>Войти
                                        </a></li>
                                    </ul>
                                </li>
                            </ul>
HTML;
}

        $template .= <<<HTML
                        </div>
                    </div>
                </nav>
            </header>
HTML;

        // Добавим flash сообщение
        if (isset($_SESSION['flash'])) {
            $template .= <<<HTML
                <div id="liveAlertBtn" class="container alert alert-custom alert-dismissible fade show" role="alert">
                    <div>{$_SESSION['flash']}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="this.parentNode.style.display='none';"></button>
                </div>
            HTML;
            unset($_SESSION['flash']);
        }

        $template .= <<<HTML
            <main class="container mt-4">
                %s
            </main>
            <footer class="mt-5 py-5">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-md-4 mb-4">
                            <h5 class="text-uppercase fw-bold">Контакты</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-map-marker-alt me-2"></i><span class="text-light">Адрес: г. Кемерово, ул. Тухочевского, 32</span></li>
                                <li><i class="fas fa-phone me-2"></i><span class="text-light">Телефон: +7 (999) 777-99-71</span></li>
                                <li><i class="fas fa-envelope me-2"></i><span class="text-light">Email: info@bubblepizza.ru</span></li>
                            </ul>
                        </div>
                        <div class="col-md-4 mb-4 text-center">
                            <h5 class="text-uppercase fw-bold">Мы в социальных сетях</h5>
                            <div class="d-flex justify-content-center gap-3 mt-3">
                                <a href="https://vk.com" target="_blank" class="social-icon-footer">
                                    <i class="fab fa-vk fa-2x"></i>
                                </a>
                                <a href="https://instagram.com" target="_blank" class="social-icon-footer">
                                    <i class="fab fa-instagram fa-2x"></i>
                                </a>
                                <a href="https://telegram.org" target="_blank" class="social-icon-footer">
                                    <i class="fab fa-telegram fa-2x"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5 class="text-uppercase fw-bold">Видео о нас</h5>
                            <div class="ratio ratio-16x9">
                                <iframe 
                                    src="https://rutube.ru/play/embed/938c7ce98486d2b597640b4bbb236550?autoplay=1" 
                                    allow="autoplay; fullscreen" 
                                    allowfullscreen 
                                    title="Видео с Rutube"
                                    style="border: none; border-radius: 8px;"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col text-center">
                            <p class="mb-0 small text-light">&copy; 2025 «Bubble Pizza» | Все права защищены</p>
                            <p class="mb-0 small text-light">Разработано студентами группы ИС-221</p>
                        </div>
                    </div>
                </div>
            </footer>
        </body>
        </html>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dropdownToggle = document.getElementById('navbarDropdown');
                const dropdownArrow = document.getElementById('dropdownArrow');
                
                if (dropdownToggle) {
                    dropdownToggle.addEventListener('click', function () {
                        setTimeout(() => {
                            const isShown = dropdownToggle.getAttribute('aria-expanded') === 'true';
                            dropdownArrow.classList.toggle('open', isShown);
                        }, 10);
                    });
                }

                // Анимация для кнопки входа
                const registerDropdown = document.getElementById('registerDropdown');
                if (registerDropdown) {
                    registerDropdown.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                    });
                    registerDropdown.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                }

                // Фикс для позиционирования выпадающего меню
                const userDropdown = document.querySelector('.user-dropdown-menu');
                if (userDropdown) {
                    const rect = userDropdown.getBoundingClientRect();
                    if (rect.right > window.innerWidth) {
                        userDropdown.style.left = 'auto';
                        userDropdown.style.right = '0';
                    }
                }
            });

            // Дополнительный фикс для ресайза окна
            window.addEventListener('resize', function() {
                const userDropdown = document.querySelector('.user-dropdown-menu');
                if (userDropdown) {
                    // Перепроверяем позиционирование при ресайзе
                    const rect = userDropdown.getBoundingClientRect();
                    if (rect.right > window.innerWidth) {
                        userDropdown.style.left = 'auto';
                        userDropdown.style.right = '0';
                    }
                }
            });
        </script>
    </body>
    </html>
HTML;
        
        return $template;
    }
}