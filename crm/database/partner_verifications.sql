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
-- Структура таблицы `partner_verifications`
--

CREATE TABLE `partner_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `partner_id` bigint UNSIGNED NOT NULL,
  `checklist_id` bigint UNSIGNED NOT NULL,
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partner_verifications`
--

INSERT INTO `partner_verifications` (`id`, `partner_id`, `checklist_id`, `status`, `verified_at`, `verified_by_user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'not_started', NULL, NULL, NULL, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(2, 2, 2, 'not_started', NULL, NULL, NULL, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(3, 3, 3, 'not_started', NULL, NULL, NULL, '2026-04-25 13:06:22', '2026-04-25 13:06:22');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `partner_verifications`
--
ALTER TABLE `partner_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `partner_verifications_partner_id_checklist_id_unique` (`partner_id`,`checklist_id`),
  ADD KEY `partner_verifications_checklist_id_foreign` (`checklist_id`),
  ADD KEY `partner_verifications_verified_by_user_id_foreign` (`verified_by_user_id`),
  ADD KEY `partner_verifications_status_index` (`status`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `partner_verifications`
--
ALTER TABLE `partner_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `partner_verifications`
--
ALTER TABLE `partner_verifications`
  ADD CONSTRAINT `partner_verifications_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `verification_checklists` (`id`),
  ADD CONSTRAINT `partner_verifications_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partner_verifications_verified_by_user_id_foreign` FOREIGN KEY (`verified_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
