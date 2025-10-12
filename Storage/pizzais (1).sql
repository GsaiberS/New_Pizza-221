-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 12 2025 г., 13:00
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pizzais`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `fio` varchar(120) NOT NULL,
  `addres` mediumtext NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(120) NOT NULL,
  `all_sum` float NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID пользователя',
  `status` int(11) DEFAULT NULL COMMENT 'Cтатус заказа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `fio`, `addres`, `phone`, `email`, `all_sum`, `created`, `payment_method`, `user_id`, `status`) VALUES
(10, 'Эчпочмак с Горячим Чаем', 'Марковцева 24', '79333009971', 'soborovets@gmail.com', 967, '2025-09-21 14:46:31', 'card', 42, 3),
(11, 'Эчпочмак с Горячим Чаем', 'Марковцева 24', '79333009971', 'soborovets@gmail.com', 967, '2025-09-21 14:47:21', 'card', 42, 1),
(12, 'Эчпочмак с Горячим Чаем', 'Марковцева 24', '79333009971', 'soborovets@gmail.com', 967, '2025-09-21 14:47:24', 'card', 42, 1),
(13, '111', 'Марковцева 24', '79333009971', 'mazariroman@yandex.ru', 219, '2025-09-21 16:08:39', 'sbp', 42, 1),
(14, '111', 'Марковцева 24', '79333009971', 'mazariroman@yandex.ru', 479, '2025-09-21 17:10:29', 'sbp', 0, 1),
(15, '111', 'Марковцева 24', '79333009971', 'mazariroman@yandex.ru', 529, '2025-09-21 17:34:05', 'sbp', 0, 1),
(16, '111', 'Марковцева 24', '79333009971', 'mazariroman@yandex.ru', 199, '2025-09-21 17:34:23', 'sbp', 0, 1),
(17, '111', 'Марковцева 24', '79333009971', 'mazariroman@yandex.ru', 199, '2025-09-21 17:40:44', 'sbp', 0, 1),
(18, '111', 'Марковцева 24', '79333009971', 'mazariroman@yandex.ru', 199, '2025-09-21 17:42:16', 'sbp', 42, 1),
(19, 'gsaibers', 'Марковцева 24', '+7 933 300 99 7', 'soborovets@gmail.com', 3473, '2025-09-29 21:00:51', 'sbp', 26, 1),
(20, 'gsaibers', 'Марковцева 24', '+7 933 300 99 7', 'soborovets@gmail.com', 3473, '2025-09-29 21:01:19', 'sbp', 26, 1),
(21, 'gsaibers', 'Марковцева 24', '79333009971', 'soborovets@gmail.com', 1587, '2025-09-29 21:43:57', 'sbp', 26, 1),
(22, 'gsaibers', 'Марковцева 24', '79333009971', 'soborovets@gmail.com', 479, '2025-10-12 17:40:10', 'sbp', 26, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `count_item` int(11) NOT NULL,
  `price_item` float NOT NULL,
  `sum_item` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `product_id`, `count_item`, `price_item`, `sum_item`) VALUES
(27, 10, 2, 1, 529, 529),
(28, 10, 10, 1, 219, 219),
(29, 10, 11, 1, 219, 219),
(30, 11, 2, 1, 529, 529),
(31, 11, 10, 1, 219, 219),
(32, 11, 11, 1, 219, 219),
(33, 12, 2, 1, 529, 529),
(34, 12, 10, 1, 219, 219),
(35, 12, 11, 1, 219, 219),
(36, 13, 11, 1, 219, 219),
(37, 14, 3, 1, 479, 479),
(38, 15, 2, 1, 529, 529),
(39, 16, 9, 1, 199, 199),
(40, 17, 9, 1, 199, 199),
(41, 18, 9, 1, 199, 199),
(42, 19, 2, 1, 529, 529),
(43, 19, 3, 5, 479, 2395),
(44, 19, 7, 1, 549, 549),
(45, 20, 2, 1, 529, 529),
(46, 20, 3, 5, 479, 2395),
(47, 20, 7, 1, 549, 549),
(48, 21, 2, 3, 529, 1587),
(49, 22, 3, 1, 479, 479);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(120) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(120) NOT NULL,
  `price` float NOT NULL,
  `category` varchar(120) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `new_id` int(11) DEFAULT NULL,
  `new_id1` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `category`, `created`, `updated`, `new_id`, `new_id1`) VALUES
(2, 'Диабло', 'Острые колбаски чоризо , острый перец халапеньо , соус барбекю, митболы из говядины , томаты, моцарелла, фирменный томатный соус', '/assets/image/2.png', 529, 'pizza', '2025-04-07 09:10:16', '2025-04-07 09:10:16', 2, NULL),
(3, 'Кола-барбекю', 'Пряная говядина , пикантная пепперони , острые колбаски чоризо , соус кола-барбекю, моцарелла, фирменный томатный соус', '/assets/image/3.png', 479, 'pizza', '2025-04-07 09:12:12', '2025-04-07 09:12:12', 3, NULL),
(4, 'Картошка Фри', 'Хрустящяя, золотистая Картошка Фри', '/assets/image/4.png', 137, 'snack', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 4, NULL),
(5, 'Добрый Кола', 'Сладкая газировка 1л', '/assets/image/5.png', 120, 'drink', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 5, NULL),
(6, 'Сырный Соус', 'Мега СЫЫЫРНЫЙ!!! соус', '/assets/image/6.png', 70, 'sauce', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 6, NULL),
(7, 'Ветчина и сыр', 'Ветчина , моцарелла, фирменный соус альфред', '/assets/image/7.png', 549, 'pizza', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 7, NULL),
(8, 'Баварский ланчбокс', 'Цельные креветки в хрустящей панировке', '/assets/image/8.png', 199, 'snack', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 8, NULL),
(9, 'Креветки', 'Цельные креветки в хрустящей панировке', '/assets/image/9.png', 199, 'snack', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 9, NULL),
(10, 'Супермясной Додстер', 'Горячая закуска с цыпленком, моцареллой, митболами, острыми колбасками чоризо и соусом бургер в тонкой пшеничной лепешке', '/assets/image/10.png', 219, 'snack', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 10, NULL),
(11, 'Морс Черная смородина', 'Фирменный ягодный морс из натуральной душистой черной смородины Дизайн товара может отличаться', '/assets/image/11.png', 219, 'drink', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 11, NULL),
(12, 'Чесночный', 'Фирменный соус с чесночным вкусом для бортиков пиццы и горячих закусок, 25 г', '/assets/image/12.png', 70, 'sauce', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 12, NULL),
(13, 'Малиновое варенье', 'Идеально к сырникам, но у нас их нет XD 25 г', '/assets/image/13.png', 70, 'sauce', '2025-04-07 09:13:14', '2025-04-07 09:13:14', 13, NULL),
(14, 'Мясная с аджикой', 'Баварские колбаски , острый соус аджика, острые колбаски чоризо , цыпленок , пикантная пепперони , моцарелла, фирменный томатный соус', '/assets/image/1.png', 469, 'pizza', '2025-04-07 09:08:44', '2025-04-07 09:08:44', 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roomId` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('vip','base','home','home_vip') DEFAULT 'base',
  `status` enum('free','booked','busy','maintenance') DEFAULT 'free',
  `number` varchar(10) DEFAULT NULL,
  `cpu` varchar(255) DEFAULT NULL,
  `gpu` varchar(255) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `monitor` varchar(255) DEFAULT NULL,
  `peripherals` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `roomId`, `description`, `image`, `price`, `category`, `status`, `number`, `cpu`, `gpu`, `ram`, `monitor`, `peripherals`, `created_at`, `updated_at`) VALUES
(1, 'VIP Room 1', 1, 'Премиальная комната с мощным оборудованием', '/assets/image/444.png', 500.00, 'vip', 'free', '101', 'Intel Core i9-13900K', 'NVIDIA RTX 4090', '64GB DDR5', '34\" 4K IPS', 'RGB-клавиатура, игровая мышь', '2025-06-04 15:03:03', '2025-06-08 08:58:50'),
(2, 'Base Room 1', 2, 'Стандартная комната для комфортной игры', '/assets/image/base.png', 300.00, 'base', 'free', '201', 'AMD Ryzen 7 5800X', 'NVIDIA RTX 3060', '16GB DDR4', '27\" Full HD', 'Обычная клавиатура, мышь', '2025-06-04 15:03:03', '2025-06-22 15:23:18'),
(3, 'Home Room 1', 3, 'Уютная комната для длительных сессий', '/assets/image/222.png', 200.00, 'home', 'busy', '301', 'Intel Core i5-12400F', 'AMD RX 6700 XT', '32GB DDR4', '24\" Full HD', 'Комплект периферии', '2025-06-04 15:03:03', '2025-06-10 13:32:21'),
(4, 'VIP Home Room 1', 4, 'Домашняя VIP-комната с высоким комфортом', '/assets/image/333.png', 400.00, 'home_vip', 'maintenance', '401', 'AMD Ryzen 9 7950X', 'NVIDIA RTX 4070 Ti', '32GB DDR5', '32\" QHD', 'RGB-периферия', '2025-06-04 15:03:03', '2025-06-10 13:32:21'),
(5, 'Base Room 2', 5, 'Стандартная комната для комфортной игры', '/assets/image/base.png', 300.00, 'base', 'free', '202', 'AMD Ryzen 7 5800X', 'NVIDIA RTX 3060', '16GB DDR4', '27\" Full HD', 'Обычная клавиатура, мышь', '2025-06-10 13:21:04', '2025-06-10 13:32:21');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT '',
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(20) DEFAULT NULL,
  `oauth_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `token`, `is_verified`, `created_at`, `address`, `phone`, `avatar`, `oauth_provider`, `oauth_id`) VALUES
(26, 'gsaibers', 'admin', 'soborovets@gmail.com', '$2y$10$NN.gpA5o9Og8opoDnywzZ.9ABNxl2YlPACC.OxP9vyxQBfC.ATP8y', '', 1, '2025-04-22 10:27:59', 'Марковцева 24', '9333009971', '/assets/uploads/avatar_68eb88e03d7080.09644924.png', NULL, NULL),
(30, 'test_user', '', 'test30@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'random_token_123', 1, '2025-05-18 16:55:26', NULL, NULL, NULL, NULL, NULL),
(43, '111', '', 'mazariroman@yandex.ru1', '$2y$10$FBu1AfbmnY09k7/Gqvl7wOJniAuUb74Xm3zT54R28JaGxvOf1AtKO', 'b40ee2a41b2c53724e8ce77e908030b3', 0, '2025-09-22 16:20:50', NULL, NULL, NULL, NULL, NULL),
(44, '111', '', 'mazarddiroman@yandex.ru', '$2y$10$LUOsJDq7XyKK/gk2LZUS8eecOxdnqZSmKcTZK8gYv2kBrFufz1EUK', 'fab87fe2ff1546cceae5c39ecaaaf0ca', 0, '2025-09-22 16:21:05', NULL, NULL, NULL, NULL, NULL),
(47, 'MAZARI Роман', '', 'mazariroman@yandex.ru', NULL, '', 1, '2025-10-11 15:29:31', '', '', '', 'yandex', '1022521939');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
