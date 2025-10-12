<?php
namespace App\Views;
use App\Views\BaseTemplate;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate()
    {
        $template = parent::getTemplate();
        $title = 'О нас - Bubble Pizza';

        $content = <<<HTML
        <style>
            /* Анимации */
            @keyframes float {
                0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); }
                25% { transform: translateY(-15px) translateX(8px) rotate(3deg); }
                50% { transform: translateY(8px) translateX(-12px) rotate(-2deg); }
                75% { transform: translateY(-10px) translateX(-8px) rotate(2deg); }
            }
            @keyframes slideUp {
                to { opacity: 1; transform: translateY(0); }
            }

            /* Герой-секция */
            .about-hero {
                background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 50%, rgba(102,126,234,0.1) 100%);
                border-radius: 25px;
                padding: 4rem 2rem;
                margin-bottom: 3rem;
                position: relative;
                overflow: hidden;
                text-align: center;
            }
            .about-hero::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(102,126,234,0.05) 0%, transparent 70%);
                animation: float 20s ease-in-out infinite;
            }
            .floating-pizza {
                position: absolute;
                font-size: 2.5rem;
                opacity: 0.1;
                animation: float 8s ease-in-out infinite;
                z-index: 1;
            }
            .pizza-1 { top: 10%; left: 10%; animation-delay: 0s; }
            .pizza-2 { top: 20%; right: 15%; animation-delay: 2s; }
            .pizza-3 { bottom: 15%; left: 20%; animation-delay: 4s; }
            .pizza-4 { bottom: 25%; right: 10%; animation-delay: 6s; }
            
            /* Заголовок с иконкой */
            .main-title {
                font-size: 3rem;
                font-weight: 800;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
                position: relative;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1rem;
            }
            .title-icon {
                width: 75px;
                height: 75px;
                border-radius: 15px;
                object-fit: contain;
            }


            /* Информационные карточки */
            .info-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                padding: 2.5rem;
                margin-bottom: 2rem;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: all 0.4s ease;
                opacity: 0;
                transform: translateY(30px);
                animation: slideUp 0.8s ease forwards;
                height: 100%;
                position: relative;
                overflow: hidden;
            }
            .info-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(102,126,234, 0.15);
            }

            /* Заголовки карточек */
            .card-title {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 2rem;
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-align: center;
            }

            /* КРУГЛЫЕ ИКОНКИ ДЛЯ КОНТАКТНОЙ ИНФОРМАЦИИ */
            .contact-icon-circle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.3rem;
                box-shadow: 0 4px 12px rgba(102,126,234, 0.3);
                flex-shrink: 0;
                margin-right: 1rem;
            }

            /* Контактная информация - ВЫРАВНИВАНИЕ ПО ЛЕВОМУ КРАЮ */
            .contact-column {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                width: 100%;
                max-width: 500px;
            }
            .contact-item {
                display: flex;
                align-items: center;
                width: 100%;
                padding: 1rem 1.5rem;
                border-radius: 15px;
                transition: all 0.3s ease;
                background: rgba(102,126,234, 0.05);
                border: 1px solid rgba(102,126,234, 0.1);
                text-align: left;
            }
            .contact-item:hover {
                background: rgba(102,126,234, 0.1);
                transform: translateX(5px);
            }
            .contact-item-content {
                text-align: left;
                flex: 1;
            }
            .contact-item-content strong {
                display: block;
                margin-bottom: 0.25rem;
                color: #333;
            }
            .contact-item-content span {
                color: #666;
            }

            /* Социальные кнопки */
            .social-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                justify-items: center;
                max-width: 400px;
                margin: 0 auto;
            }
            .social-btn-custom {
                background: linear-gradient(135deg, #667eea, #764ba2);
                border: none;
                color: white !important;
                border-radius: 15px;
                padding: 12px 20px;
                transition: all 0.3s ease;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                font-weight: 600;
                box-shadow: 0 4px 15px rgba(102,126,234, 0.3);
            }
            .social-btn-custom:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(102,126,234, 0.4);
            }
            .social-btn-custom i {
                color: white !important;
                margin-right: 8px;
                font-size: 1.2rem;
            }

            /* Карта */
            .map-container {
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                opacity: 0;
                transform: translateY(30px);
                animation: slideUp 0.8s ease 0.7s forwards;
                margin: 3rem auto;
                border: 1px solid rgba(255, 255, 255, 0.3);
                max-width: 900px;
            }
            .map-container:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }

            /* Разделитель */
            .bubble-divider {
                height: 3px;
                background: linear-gradient(90deg, transparent, #667eea, transparent);
                margin: 3rem auto;
                border: none;
                border-radius: 2px;
                max-width: 800px;
            }

            /* Сетка для карточек */
            .equal-height-cards {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }
            .equal-height-cards .col-lg-6 {
                display: flex;
                flex: 1;
                min-width: 300px;
                max-width: 500px;
            }
            .centered-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                width: 100%;
            }

            /* Адаптивность */
            @media (max-width: 768px) {
                .main-title { 
                    font-size: 2.2rem; 
                    flex-direction: column;
                    gap: 0.5rem;
                }
                .title-icon {
                    width: 40px;
                    height: 40px;
                }
                .social-grid { grid-template-columns: 1fr; max-width: 300px; }
                .contact-icon-circle { width: 50px; height: 50px; font-size: 1.1rem; margin-right: 0.8rem; }
                .info-card { padding: 2rem 1.5rem; }
                .contact-item { padding: 1rem; }
                .equal-height-cards .col-lg-6 { min-width: 100%; }
            }
        </style>

        <div class="container mt-4">
            <div class="about-hero text-center position-relative">
                <div class="floating-pizza pizza-1">🍕</div>
                <div class="floating-pizza pizza-2">🍕</div>
                <div class="floating-pizza pizza-3">🍕</div>
                <div class="floating-pizza pizza-4">🍕</div>
                <h1 class="main-title">
                    <img src="/assets/image/BP.ico" alt="Bubble Pizza" class="title-icon">
                    О Bubble Pizza
                </h1>
                <p class="lead fs-4 text-dark opacity-75 mb-3">Самые воздушные пиццы с невероятным вкусом!</p>
                <p class="text-muted fs-5">Кемеровский кооперативный техникум — это первый шаг на пути к будущей успешной карьере</p>
            </div>

            <!-- Контактная информация -->
            <div class="equal-height-cards">
                <div class="col-lg-6 mb-4">
                    <div class="info-card h-100 centered-content">
                        <div class="card-title">Контактная информация</div>
                        <div class="contact-column">
                            <div class="contact-item">
                                <div class="contact-icon-circle">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-item-content">
                                    <strong>Адрес</strong>
                                    <span>г. Кемерово, ул. Тухочевского, 32</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon-circle">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-item-content">
                                    <strong>Время работы</strong>
                                    <span>Ежедневно с 10:00 до 23:00</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon-circle">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-item-content">
                                    <strong>Телефон</strong>
                                    <span>+7 (999) 777-99-71</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon-circle">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-item-content">
                                    <strong>Email</strong>
                                    <span>info@bubblepizza.ru</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="info-card h-100 centered-content">
                        <div class="card-title">Ссылки на разработчика</div>
                        <div class="social-grid">
                            <a href="https://github.com/GsaiberS" target="_blank" class="social-btn-custom">
                                <i class="fab fa-github"></i> GitHub
                            </a>
                            <a href="https://vk.com/rsoborovets" target="_blank" class="social-btn-custom">
                                <i class="fab fa-vk"></i> VK
                            </a>
                            <a href="https://t.me/Rsobr" target="_blank" class="social-btn-custom">
                                <i class="fab fa-telegram"></i> Telegram
                            </a>
                            <a href="https://steamcommunity.com/profiles/76561199438628487/" target="_blank" class="social-btn-custom">
                                <i class="fab fa-steam"></i> Steam
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="bubble-divider">

            <!-- Карта -->
            <div class="map-container">
                <div class="centered-content p-4">
                    <div class="contact-icon-circle mb-3">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="card-title mb-4">Мы на карте</h3>
                </div>
                <div style="position:relative;overflow:hidden; border-radius: 16px;">
                    <iframe 
                        src="https://yandex.ru/map-widget/v1/?ll=86.133386%2C55.332456&mode=poi&poi%5Bpoint%5D=86.133796%2C55.333990&poi%5Buri%5D=ymapsbm1%3A%2F%2Forg%3Foid%3D1018378103&z=17.14" 
                        width="100%" 
                        height="400" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        style="border: none;">
                    </iframe>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Анимация появления элементов при скролле
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.animationPlayState = 'running';
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.info-card, .map-container').forEach(el => {
                    observer.observe(el);
                });

                // Выравнивание высоты карточек
                function equalizeCards() {
                    const cards = document.querySelectorAll('.info-card');
                    let maxHeight = 0;

                    cards.forEach(card => {
                        card.style.height = 'auto';
                        const height = card.offsetHeight;
                        if (height > maxHeight) {
                            maxHeight = height;
                        }
                    });

                    cards.forEach(card => {
                        card.style.height = maxHeight + 'px';
                    });
                }

                window.addEventListener('load', equalizeCards);
                window.addEventListener('resize', equalizeCards);
            });
        </script>
HTML;

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}