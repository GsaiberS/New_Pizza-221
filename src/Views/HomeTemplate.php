<?php

namespace App\Views;

use App\Views\BaseTemplate;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();

        $content = <<<HTML
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: 
        radial-gradient(circle at 20% 80%, rgba(208,157,176,0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(102,126,234,0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(118,75,162,0.1) 0%, transparent 50%),
        linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f8f9fa 100%);
    background-size: cover;
    background-attachment: fixed;
    color: #343a40;
    margin: 0;
    padding: 0;
}

/* ===== HERO ===== */
.hero {
    position: relative;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 25px;
    padding: 80px 20px;
    margin: 40px auto;
    width: 90%;
    max-width: 1100px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(102,126,234,0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(208,157,176,0.05) 0%, transparent 70%);
    animation: float 15s ease-in-out infinite;
}

.hero h1 {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(135deg, rgb(208,157,176), #667eea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 15px;
}

.hero p {
    color: #444;
    font-size: 1.3rem;
    margin-bottom: 30px;
}

.hero .btn-main {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-weight: 600;
    padding: 14px 35px;
    border-radius: 50px;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    border: none;
    position: relative;
    overflow: hidden;
}
.hero .btn-main::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}
.hero .btn-main:hover::before {
    left: 100%;
}
.hero .btn-main:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.5);
}

/* ===== SECTIONS ===== */
.section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    margin: 40px auto;
    padding: 60px 20px;
    width: 90%;
    max-width: 1100px;
    border-radius: 25px;
    box-shadow: 0 10px 30px rgba(102,126,234,0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    text-align: center;
}

.section h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 40px;
    background: linear-gradient(135deg, rgb(208,157,176), #667eea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ===== ADVANTAGES ===== */
.advantages {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 25px;
}

.card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px 15px;
    box-shadow: 0 6px 20px rgba(102,126,234,0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(102,126,234,0.15);
}
.card i {
    font-size: 2rem;
    background: linear-gradient(135deg, rgb(208,157,176), #667eea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 15px;
}
.card h3 {
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
}
.card p {
    color: #666;
    font-size: 0.9rem;
}

/* ===== HITS ===== */
.hits {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
}

.hit {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    width: 300px;
    box-shadow: 0 8px 25px rgba(102,126,234,0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}
.hit:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(102,126,234,0.2);
}
.hit img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.hit-info {
    padding: 15px;
}
.hit-info h4 {
    font-weight: 600;
    background: linear-gradient(135deg, rgb(208,157,176), #667eea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hit-info p {
    color: #555;
    font-size: 0.95rem;
}

/* ===== REVIEWS ===== */
.reviews {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}
.review {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 6px 15px rgba(102,126,234,0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    text-align: left;
    transition: all 0.3s ease;
}
.review:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(102,126,234,0.12);
}
.review .stars {
    color: rgb(208,157,176);
    margin-bottom: 10px;
}
.review p {
    color: #555;
    font-style: italic;
    margin-bottom: 10px;
}
.review .author {
    font-weight: 600;
    background: linear-gradient(135deg, rgb(208,157,176), #667eea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ===== АНИМАЦИЯ ===== */
@keyframes fadeUp {
    0% {opacity:0; transform: translateY(40px);}
    100% {opacity:1; transform: translateY(0);}
}

@keyframes float {
    0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); }
    25% { transform: translateY(-15px) translateX(8px) rotate(3deg); }
    50% { transform: translateY(8px) translateX(-12px) rotate(-2deg); }
    75% { transform: translateY(-10px) translateX(-8px) rotate(2deg); }
}

.hero, .section, .card, .hit, .review {
    animation: fadeUp 0.8s ease both;
}

/* Floating pizza decorations */
.floating-pizza {
    position: absolute;
    font-size: 2rem;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}
.pizza-1 { top: 10%; left: 5%; animation-delay: 0s; }
.pizza-2 { top: 60%; right: 10%; animation-delay: 2s; }
.pizza-3 { bottom: 20%; left: 15%; animation-delay: 4s; }

/* Animation delays for cards */
.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
.card:nth-child(5) { animation-delay: 0.5s; }
.card:nth-child(6) { animation-delay: 0.6s; }

.hit:nth-child(1) { animation-delay: 0.2s; }
.hit:nth-child(2) { animation-delay: 0.4s; }
.hit:nth-child(3) { animation-delay: 0.6s; }

.review:nth-child(1) { animation-delay: 0.1s; }
.review:nth-child(2) { animation-delay: 0.3s; }
.review:nth-child(3) { animation-delay: 0.5s; }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- HERO -->
<section class="hero">
    <div class="floating-pizza pizza-1">🍕</div>
    <div class="floating-pizza pizza-2">🍕</div>
    <div class="floating-pizza pizza-3">🍕</div>
    
    <h1>Добро пожаловать в Bubble Pizza 🍕</h1>
    <p>Самые воздушные пиццы с невероятным вкусом!</p>
    <a href="/products" class="btn-main">🍽 Посмотреть меню</a>
</section>

<!-- ADVANTAGES -->
<section class="section">
    <h2>Почему выбирают нас?</h2>
    <div class="advantages">
        <div class="card">
            <i class="fa-solid fa-pizza-slice"></i>
            <h3>Лучшие ингредиенты</h3>
            <p>Только свежие, проверенные продукты от локальных поставщиков.</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-truck-fast"></i>
            <h3>Быстрая доставка</h3>
            <p>Доставим горячую пиццу за 30 минут!</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-star"></i>
            <h3>Фирменный вкус</h3>
            <p>Авторские рецепты и идеальные сочетания ингредиентов.</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-gift"></i>
            <h3>Акции и бонусы</h3>
            <p>Постоянные скидки и купоны для наших гостей.</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-hand-holding-heart"></i>
            <h3>Любовь к клиентам</h3>
            <p>Каждый заказ — с заботой, вниманием и улыбкой.</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-leaf"></i>
            <h3>Экологичность</h3>
            <p>Биоразлагаемая упаковка и забота о планете.</p>
        </div>
    </div>
</section>

<!-- HITS -->
<section class="section">
    <h2>Попробуйте хиты недели 🔥</h2>
    <div class="hits">
        <div class="hit">
            <img src="/assets/image/7.png" alt="Пицца Маргарита">
            <div class="hit-info">
                <h4>Пицца Ветчина с сыром</h4>
                <p>Классика с томатным соусом, моцареллой и ветчиной.</p>
            </div>
        </div>
        <div class="hit">
            <img src="/assets/image/3.png" alt="Пицца Пепперони">
            <div class="hit-info">
                <h4>Пицца Кола-Барбекю</h4>
                <p>Пикантная колбаса, сыр и идеальный томатный соус со вкусом колы.</p>
            </div>
        </div>
        <div class="hit">
            <img src="/assets/image/2.png" alt="Пицца BBQ">
            <div class="hit-info">
                <h4>Пицца Диабло</h4>
                <p>Колбаски чоризо, говядина и острые халапеньо.</p>
            </div>
        </div>
    </div>
</section>

<!-- REVIEWS -->
<section class="section">
    <h2>Отзывы клиентов 💬</h2>
    <div class="reviews">
        <div class="review">
            <div class="stars">★★★★★</div>
            <p>"Отличная пицца! Всегда свежая и ароматная ❤️"</p>
            <div class="author">— Анна К.</div>
        </div>
        <div class="review">
            <div class="stars">★★★★★</div>
            <p>"Быстро, вкусно и недорого. Обожаю ваш сервис!"</p>
            <div class="author">— Иван П.</div>
        </div>
        <div class="review">
            <div class="stars">★★★★☆</div>
            <p>"Доставка вовремя, пицца горячая. Советую всем друзьям!"</p>
            <div class="author">— Мария С.</div>
        </div>
    </div>
</section>

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
    
    // Наблюдаем за всеми анимированными элементами
    document.querySelectorAll('.hero, .section, .card, .hit, .review').forEach(el => {
        observer.observe(el);
    });
});
</script>
HTML;

        return sprintf($template, 'Главная - Bubble Pizza', $content);
    }
}