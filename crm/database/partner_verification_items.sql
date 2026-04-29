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
-- Структура таблицы `partner_verification_items`
--

CREATE TABLE `partner_verification_items` (
  `id` bigint UNSIGNED NOT NULL,
  `partner_verification_id` bigint UNSIGNED NOT NULL,
  `checklist_item_id` bigint UNSIGNED NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  `checked_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partner_verification_items`
--

INSERT INTO `partner_verification_items` (`id`, `partner_verification_id`, `checklist_item_id`, `is_checked`, `checked_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, NULL, NULL, '2026-04-25 18:03:33', '2026-04-25 18:03:33'),
(2, 1, 2, 0, NULL, NULL, '2026-04-25 18:03:33', '2026-04-25 18:03:33'),
(3, 1, 3, 0, NULL, NULL, '2026-04-25 18:03:33', '2026-04-25 18:03:33'),
(4, 1, 4, 0, NULL, NULL, '2026-04-25 18:03:33', '2026-04-25 18:03:33'),
(5, 1, 5, 0, NULL, NULL, '2026-04-25 18:03:33', '2026-04-25 18:03:33'),
(6, 1, 6, 0, NULL, NULL, '2026-04-25 18:03:33', '2026-04-25 18:03:33');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `partner_verification_items`
--
ALTER TABLE `partner_verification_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pvi_verif_item_unique` (`partner_verification_id`,`checklist_item_id`),
  ADD KEY `partner_verification_items_checklist_item_id_foreign` (`checklist_item_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `partner_verification_items`
--
ALTER TABLE `partner_verification_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `partner_verification_items`
--
ALTER TABLE `partner_verification_items`
  ADD CONSTRAINT `partner_verification_items_checklist_item_id_foreign` FOREIGN KEY (`checklist_item_id`) REFERENCES `verification_checklist_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partner_verification_items_partner_verification_id_foreign` FOREIGN KEY (`partner_verification_id`) REFERENCES `partner_verifications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
