-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 29 2026 г., 15:24
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `medical07_crm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `partner_layers`
--

CREATE TABLE `partner_layers` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partner_layers`
--

INSERT INTO `partner_layers` (`id`, `code`, `name`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'clinic_ipo', 'Клиники / International Patient Office (IPO)', 'Принимают пациента, ставят диагноз, оказывают лечение. Предпочтительный контакт — международный отдел (IPO) клиники.', 1, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(2, 'translator', 'Переводчики с медицинской специализацией', 'Письменный перевод медицинских документов; устный перевод на онлайн-консультациях. SLA: 24–72 ч, понятное ценообразование.', 2, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(3, 'curator', 'Медицинский редактор / куратор кейса', 'Врач-консультант, который помогает структурировать вопросы и проверить правильность сборки кейса (без постановки диагноза пациенту напрямую).', 3, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(4, 'imported', 'Импорт (Research)', NULL, 99, '2026-04-26 06:40:08', '2026-04-26 06:40:08');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `partner_layers`
--
ALTER TABLE `partner_layers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `partner_layers_code_unique` (`code`),
  ADD KEY `partner_layers_sort_order_index` (`sort_order`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `partner_layers`
--
ALTER TABLE `partner_layers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
