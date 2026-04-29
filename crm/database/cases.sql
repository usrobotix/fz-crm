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
-- Структура таблицы `cases`
--

CREATE TABLE `cases` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `pipeline_status_id` bigint UNSIGNED NOT NULL,
  `service_status_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_to_user_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `problem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `priority` tinyint UNSIGNED NOT NULL DEFAULT '3',
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cases`
--

INSERT INTO `cases` (`id`, `patient_id`, `pipeline_status_id`, `service_status_id`, `assigned_to_user_id`, `title`, `problem`, `priority`, `opened_at`, `closed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, 'Иванов Иван', 'Просит проверить Испанскую клинику', 3, '2026-04-26 07:48:27', NULL, '2026-04-26 07:48:27', '2026-04-26 15:47:17');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cases_patient_id_foreign` (`patient_id`),
  ADD KEY `cases_assigned_to_user_id_foreign` (`assigned_to_user_id`),
  ADD KEY `cases_pipeline_status_id_assigned_to_user_id_index` (`pipeline_status_id`,`assigned_to_user_id`),
  ADD KEY `cases_service_status_id_index` (`service_status_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cases`
--
ALTER TABLE `cases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_assigned_to_user_id_foreign` FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cases_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_pipeline_status_id_foreign` FOREIGN KEY (`pipeline_status_id`) REFERENCES `case_statuses` (`id`),
  ADD CONSTRAINT `cases_service_status_id_foreign` FOREIGN KEY (`service_status_id`) REFERENCES `case_statuses` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
