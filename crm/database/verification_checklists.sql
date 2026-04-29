-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 29 2026 г., 15:25
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
-- Структура таблицы `verification_checklists`
--

CREATE TABLE `verification_checklists` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `partner_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `verification_checklists`
--

INSERT INTO `verification_checklists` (`id`, `code`, `name`, `partner_type`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'clinic_verification', 'Верификация клиники / IPO', 'clinic', 'Чек-лист для проверки клиники или International Patient Office перед началом сотрудничества.', 1, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(2, 'translator_verification', 'Верификация переводчика', 'translator', 'Чек-лист для проверки переводчика с медицинской специализацией.', 2, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(3, 'curator_verification', 'Верификация медицинского редактора / куратора', 'curator', 'Чек-лист для проверки врача-куратора кейса.', 3, '2026-04-25 13:06:22', '2026-04-25 13:06:22');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `verification_checklists`
--
ALTER TABLE `verification_checklists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `verification_checklists_code_unique` (`code`),
  ADD KEY `verification_checklists_partner_type_index` (`partner_type`),
  ADD KEY `verification_checklists_sort_order_index` (`sort_order`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `verification_checklists`
--
ALTER TABLE `verification_checklists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
