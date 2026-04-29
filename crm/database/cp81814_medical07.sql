-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 29 2026 г., 15:33
-- Версия сервера: 5.7.44-48
-- Версия PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cp81814_medical07`
--

-- --------------------------------------------------------

--
-- Структура таблицы `audit_events`
--

CREATE TABLE IF NOT EXISTS `audit_events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_events_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `audit_events`
--

INSERT INTO `audit_events` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `ip`, `payload`, `created_at`) VALUES
(1, 1, 'backup.queued', 'backup', 1, '85.173.126.112', '{\"type\": \"db\", \"preset\": \"project\", \"formats\": [\"zip\", \"tar_gz\"], \"initiated_by\": \"user\"}', '2026-04-29 09:56:23'),
(2, 1, 'backup.created', 'backup', 1, NULL, '{\"type\": \"db\", \"preset\": \"project\", \"formats\": [\"zip\", \"tar_gz\"], \"initiated_by\": \"user\"}', '2026-04-29 10:04:57'),
(3, 1, 'backup.queued', 'backup', 2, '85.173.126.112', '{\"type\": \"db\", \"preset\": \"project\", \"formats\": [\"zip\"], \"initiated_by\": \"user\"}', '2026-04-29 10:06:51'),
(4, 1, 'backup.created', 'backup', 2, NULL, '{\"type\": \"db\", \"preset\": \"project\", \"formats\": [\"zip\"], \"initiated_by\": \"user\"}', '2026-04-29 10:07:04'),
(5, 1, 'backup.restore_queued', 'backup', 2, '85.173.126.112', '{\"source_backup_id\": 2, \"restore_record_id\": 3}', '2026-04-29 10:07:15'),
(6, 1, 'backup.restore_failed', 'backup', 2, NULL, '{\"error\": \"SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active.  Consider using PDOStatement::fetchAll().  Alternatively, if your code is only ever going to run against mysql, you may enable query buffering by setting the PDO::MYSQL_ATTR_USE_BUFFERED_QUERY attribute.\"}', '2026-04-29 10:08:06'),
(7, 1, 'backup.restore_queued', 'backup', 2, '85.173.126.112', '{\"source_backup_id\": 2, \"restore_record_id\": 5}', '2026-04-29 10:15:46'),
(8, 1, 'backup.restore_failed', 'backup', 2, NULL, '{\"error\": \"SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active.  Consider using PDOStatement::fetchAll().  Alternatively, if your code is only ever going to run against mysql, you may enable query buffering by setting the PDO::MYSQL_ATTR_USE_BUFFERED_QUERY attribute.\"}', '2026-04-29 10:16:04'),
(9, 1, 'backup.restore_queued', 'backup', 2, '85.173.126.112', '{\"source_backup_id\": 2, \"restore_record_id\": 7}', '2026-04-29 10:24:59'),
(10, 1, 'backup.restore_failed', 'backup', 2, NULL, '{\"error\": \"SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active.  Consider using PDOStatement::fetchAll().  Alternatively, if your code is only ever going to run against mysql, you may enable query buffering by setting the PDO::MYSQL_ATTR_USE_BUFFERED_QUERY attribute.\"}', '2026-04-29 10:25:04'),
(11, 1, 'backup.restore_queued', 'backup', 2, '85.173.126.112', '{\"source_backup_id\": 2, \"restore_record_id\": 9}', '2026-04-29 10:31:10'),
(12, 1, 'backup.restored', 'backup', 2, NULL, '{\"source_backup_id\": 2, \"restore_record_id\": 9}', '2026-04-29 10:32:06'),
(13, 1, 'backup.queued', 'backup', 3, '85.173.126.112', '{\"type\": \"db\", \"preset\": \"project\", \"formats\": [\"zip\", \"tar_gz\"], \"initiated_by\": \"user\"}', '2026-04-29 10:46:31'),
(14, 1, 'backup.restored', 'backup', 3, NULL, '{\"source_backup_id\": 3, \"restore_record_id\": 4}', '2026-04-29 10:48:06');

-- --------------------------------------------------------

--
-- Структура таблицы `backups`
--

CREATE TABLE IF NOT EXISTS `backups` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'backup',
  `file_preset` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formats` json NOT NULL,
  `local_paths` json DEFAULT NULL,
  `remote_paths` json DEFAULT NULL,
  `size_bytes` bigint(20) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `progress_percent` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `current_step` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `initiated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `backups_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `backups`
--

INSERT INTO `backups` (`id`, `type`, `kind`, `file_preset`, `formats`, `local_paths`, `remote_paths`, `size_bytes`, `status`, `progress_percent`, `current_step`, `started_at`, `finished_at`, `error_message`, `initiated_by`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'db', 'backup', NULL, '[\"zip\", \"tar_gz\"]', '{\"zip\": \"/home/c/cp81814/medical07/public_html/crm/storage/app/backups/2026-04-29/2026-04-29_130453_db.zip\", \"tar_gz\": \"/home/c/cp81814/medical07/public_html/crm/storage/app/backups/2026-04-29/2026-04-29_130453_db.tar.gz\"}', '{\"zip\": \"app:/backups/2026-04-29_130453_db.zip\", \"tar_gz\": \"app:/backups/2026-04-29_130453_db.tar.gz\"}', 29148, 'done', 100, 'done', '2026-04-29 10:04:53', '2026-04-29 10:04:57', NULL, 'user', 1, '2026-04-29 09:56:23', '2026-04-29 10:04:57'),
(3, 'db', 'backup', NULL, '[\"zip\", \"tar_gz\"]', NULL, NULL, 0, 'running', 10, 'db_dump', '2026-04-29 10:47:02', NULL, NULL, 'user', 1, '2026-04-29 10:46:31', '2026-04-29 10:47:02');

-- --------------------------------------------------------

--
-- Структура таблицы `backup_settings`
--

CREATE TABLE IF NOT EXISTS `backup_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `schedule_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '03:00',
  `backup_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `file_preset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'project',
  `formats` json DEFAULT NULL,
  `upload_yandex` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `backup_settings`
--

INSERT INTO `backup_settings` (`id`, `enabled`, `schedule_time`, `backup_type`, `file_preset`, `formats`, `upload_yandex`, `created_at`, `updated_at`) VALUES
(1, 1, '03:00', 'full', 'project', '[\"zip\", \"tar_gz\"]', 1, '2026-04-29 09:46:49', '2026-04-29 09:46:49');

-- --------------------------------------------------------

--
-- Структура таблицы `cases`
--

CREATE TABLE IF NOT EXISTS `cases` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `pipeline_status_id` bigint(20) UNSIGNED NOT NULL,
  `service_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `problem` text COLLATE utf8mb4_unicode_ci,
  `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT '3',
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cases_patient_id_foreign` (`patient_id`),
  KEY `cases_assigned_to_user_id_foreign` (`assigned_to_user_id`),
  KEY `cases_pipeline_status_id_assigned_to_user_id_index` (`pipeline_status_id`,`assigned_to_user_id`),
  KEY `cases_service_status_id_index` (`service_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `case_partner`
--

CREATE TABLE IF NOT EXISTS `case_partner` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `medical_case_id` bigint(20) UNSIGNED NOT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_partner_medical_case_id_partner_id_unique` (`medical_case_id`,`partner_id`),
  KEY `case_partner_partner_id_foreign` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `case_statuses`
--

CREATE TABLE IF NOT EXISTS `case_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_service` tinyint(1) NOT NULL DEFAULT '0',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_statuses_code_unique` (`code`),
  KEY `case_statuses_sort_order_index` (`sort_order`),
  KEY `case_statuses_is_service_index` (`is_service`),
  KEY `case_statuses_is_terminal_index` (`is_terminal`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `case_statuses`
--

INSERT INTO `case_statuses` (`id`, `sort_order`, `code`, `name`, `description`, `is_service`, `is_terminal`, `created_at`, `updated_at`) VALUES
(1, 1, 'lead_new_request', 'Лид (новый запрос)', 'есть первичное сообщение/заявка, данных мало', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 2, 'screening_clarification', 'Скрининг / уточнение', 'собраны базовые сведения: диагноз/подозрение, срочность, страна/язык, ожидания\nпринято решение: второй взгляд / пересмотр снимков / пересмотр гистологии / организация поездки', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 3, 'offer_package_agreement', 'Офер / согласование пакета', 'согласован состав работ, сроки, стоимость вашей координации\nвыставлен счёт/ссылка на оплату, отправлены условия сервиса', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 4, 'payment_received', 'Оплата получена', 'оплачена ваша работа (координация/упаковка кейса). Оплаты клинике/врачу — отдельно, по правилам выбранного провайдера', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(5, 5, 'documents_collection', 'Сбор документов', 'получены выписки/анализы/заключения\nполучены файлы DICOM (КТ/МРТ) при необходимости', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(6, 6, 'translation_case_preparation', 'Перевод / подготовка кейса', 'выполнен перевод ключевых документов\nподготовлен Medical case summary (хронология + список вопросов)', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(7, 7, 'clinic_doctor_selection', 'Подбор клиники/врача', 'подобраны 1–3 варианта\nсогласованы сроки ответа и стоимость консультации', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(8, 8, 'appointment_confirmation', 'Запись / подтверждение консультации', 'консультация назначена, подтверждены время, формат, требования', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(9, 9, 'consultation_completed', 'Консультация проведена', 'получено заключение/письмо врача/результат консилиума', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(10, 10, 'result_delivered_to_client', 'Выдача результата клиенту', 'передано заключение\nпроведён разбор «человеческим языком»\nсформированы следующие шаги (next steps)', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(11, 11, 'treatment_coordination_optional', 'Координация лечения (опционально)', 'согласование плана лечения, графика процедур\nсмета/счета, организационные вопросы', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(12, 12, 'travel_treatment_optional', 'Поездка / лечение (опционально)', 'визовая поддержка (без гарантий)\nлогистика, сопровождение, переводчик', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(13, 13, 'post_followup', 'Пост‑сопровождение', 'контрольные анализы/наблюдение\nкоммуникация с клиникой по результатам', 0, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(14, 14, 'closed_success', 'Закрыто (успешно)', 'кейс завершён, собрана обратная связь', 0, 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(15, 15, 'closed_unsuccess', 'Закрыто (неуспешно/отказ)', 'клиент передумал/не смог оплатить/нет контакта/не подходит по критериям', 0, 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(16, 101, 'waiting_client', 'Ожидаю клиента', 'жду документы/ответ/оплату', 1, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(17, 102, 'waiting_partner', 'Ожидаю партнёра', 'жду переводчика/клинику/врача', 1, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(18, 103, 'stop_legal_ethical_risks', 'Стоп: юридические/этические риски', 'запрос на «серые» действия, подделки, гарантии результата и т.п.', 1, 0, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `case_status_histories`
--

CREATE TABLE IF NOT EXISTS `case_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `medical_case_id` bigint(20) UNSIGNED NOT NULL,
  `from_pipeline_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_pipeline_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `from_service_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_service_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `changed_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `change_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pipeline',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_status_histories_from_pipeline_status_id_foreign` (`from_pipeline_status_id`),
  KEY `case_status_histories_to_pipeline_status_id_foreign` (`to_pipeline_status_id`),
  KEY `case_status_histories_from_service_status_id_foreign` (`from_service_status_id`),
  KEY `case_status_histories_to_service_status_id_foreign` (`to_service_status_id`),
  KEY `case_status_histories_medical_case_id_index` (`medical_case_id`),
  KEY `case_status_histories_changed_by_user_id_index` (`changed_by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `iso2` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ru` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_iso2_unique` (`iso2`),
  KEY `countries_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `iso2`, `name_ru`, `name_en`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'TR', 'Турция', 'Turkey', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 'IL', 'Израиль', 'Israel', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 'DE', 'Германия', 'Germany', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 'ES', 'Испания', 'Spain', 4, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(5, 'AE', 'ОАЭ', 'United Arab Emirates', 5, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(6, 'FR', 'Франция', 'France', 6, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(7, 'KR', 'Южная Корея', 'South Korea', 7, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(8, 'IT', 'Италия', 'Italy', 8, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `country_directions`
--

CREATE TABLE IF NOT EXISTS `country_directions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `niche_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `what_to_look_for` text COLLATE utf8mb4_unicode_ci,
  `search_queries` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_directions_niche_id_foreign` (`niche_id`),
  KEY `country_directions_country_id_niche_id_index` (`country_id`,`niche_id`),
  KEY `country_directions_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `country_directions`
--

INSERT INTO `country_directions` (`id`, `country_id`, `niche_id`, `title`, `what_to_look_for`, `search_queries`, `notes`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Турция — общее направление', 'Онкология, ортопедия, кардиология. Много клиник с русскоязычными IPO. Конкурентный чек. Приоритетная страна для старта. Предпочтительно начинать не с топ-клиник, а с нормальных сильных центров, где реально отвечают быстро.', 'international patient coordinator + clinic name\nJCI accredited hospitals Turkey oncology', 'Приоритет для старта: быстрый ответ IPO, опыт работы с русскоязычными пациентами, понятное ценообразование.', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 2, NULL, 'Израиль — общее направление', 'Онкология (топ-уровень), генетика, репродуктология. Дорого, но высокое качество. Sheba, Hadassah, Ichilov — ключевые клиники.', 'Sheba / Hadassah / Ichilov international patients\nIsrael oncology second opinion coordinator', 'Приоритет для старта: опыт работы с русскоязычными пациентами.', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 3, NULL, 'Германия — общее направление', 'Нейрохирургия, онкология, кардиология. Высокая стоимость, строгая документация. Charité, Heidelberg, MRI München — ведущие клиники.', 'Charité / Heidelberg / MRI München Patientenmanagement\nGermany hospital international office second opinion', 'Языковой барьер меньше, чем во Франции. Строгие требования к оформлению документов.', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 4, NULL, 'Испания — общее направление', 'Онкология, ЭКО (лидер репродуктологии), ортопедия. IVI Valencia/Barcelona — лидеры репродуктологии.', 'IVI Valencia / Barcelona international patients\nSpain IVF clinic coordinator', NULL, 4, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(5, 5, NULL, 'ОАЭ — общее направление', 'Кардиология, ортопедия, косметика. Хаб для пациентов из СНГ/Ближнего Востока. Cleveland Clinic Abu Dhabi — ключевая клиника.', 'Cleveland Clinic Abu Dhabi international\nDubai hospital international patient office', NULL, 5, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(6, 6, NULL, 'Франция — общее направление', 'Онкология, нейрохирургия. Языковой барьер сильнее, нужен франкоязычный куратор. IGR Gustave Roussy, AP-HP — ведущие клиники.', 'IGR Gustave Roussy international patients\nAP-HP international office second opinion', 'Необходим переводчик с фр. языком.', 6, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(7, 7, NULL, 'Южная Корея — общее направление', 'Онкология (особенно желудок/щитовидка), репродуктология, ортопедия. Samsung Medical Center, Asan Medical Center.', 'Samsung Medical Center international\nAsan Medical Center Korea second opinion', NULL, 7, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(8, 8, NULL, 'Италия — общее направление', 'Онкология, ортопедия. Несколько центров с сильными международными отделами. IEO Milano, Humanitas.', 'IEO Milano international patients\nHumanitas international office', NULL, 8, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `country_partner`
--

CREATE TABLE IF NOT EXISTS `country_partner` (
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`partner_id`,`country_id`),
  KEY `country_partner_country_id_foreign` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `country_partner`
--

INSERT INTO `country_partner` (`partner_id`, `country_id`) VALUES
(1, 1),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(2, 'c13f46eb-1b32-4982-9a34-48650779d6c6', 'database', 'default', '{\"uuid\":\"c13f46eb-1b32-4982-9a34-48650779d6c6\",\"displayName\":\"App\\\\Jobs\\\\RunBackupJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":1,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":3600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RunBackupJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\RunBackupJob\\\":2:{s:8:\\\"backupId\\\";i:3;s:12:\\\"uploadYandex\\\";b:1;}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\RunBackupJob has been attempted too many times. in /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/MaxAttemptsExceededException.php:24\nStack trace:\n#0 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(785): Illuminate\\Queue\\MaxAttemptsExceededException::forJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(519): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#2 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#3 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#4 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(137): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#6 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#7 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#8 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#9 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#10 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#11 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#12 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call(Array)\n#13 /home/c/cp81814/medical07/public_html/crm/vendor/symfony/console/Command/Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#15 /home/c/cp81814/medical07/public_html/crm/vendor/symfony/console/Application.php(1121): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 /home/c/cp81814/medical07/public_html/crm/vendor/symfony/console/Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 /home/c/cp81814/medical07/public_html/crm/vendor/symfony/console/Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 /home/c/cp81814/medical07/public_html/crm/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 /home/c/cp81814/medical07/public_html/crm/artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2026-04-29 10:49:01');

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `job_batches`
--

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `message_templates`
--

CREATE TABLE IF NOT EXISTS `message_templates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ru',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_partner_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_templates_code_unique` (`code`),
  KEY `message_templates_channel_index` (`channel`),
  KEY `message_templates_target_partner_type_index` (`target_partner_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `message_templates`
--

INSERT INTO `message_templates` (`id`, `code`, `title`, `channel`, `language`, `subject`, `body`, `target_partner_type`, `created_at`, `updated_at`) VALUES
(1, 'email_clinic_en', 'Email клинике / IPO (английский)', 'email', 'en', 'Cooperation Inquiry — Medical Coordinator for Russian-Speaking Patients', 'Dear International Patient Office team,\n\nMy name is [Your Name]. I am a medical coordinator based in [City/Country],\nspecializing in second opinion and treatment coordination for Russian-speaking\npatients.\n\nI am reaching out to explore the possibility of establishing a cooperation\nagreement with [Clinic Name]. Specifically, I am interested in:\n\n1. Understanding your process for international patient referrals.\n2. Discussing case submission formats (medical summaries, DICOM, pathology).\n3. Learning about your standard response SLA for second opinion requests.\n4. Understanding your invoicing process for international patients.\n\nI currently work with patients primarily in the fields of [oncology / orthopedics /\ncardiology — choose relevant]. My role is strictly organizational: I prepare and\ntranslate patient cases, coordinate scheduling, and support patients throughout\nthe process. I do not provide medical advice.\n\nI would appreciate a 20–30 minute call or a brief overview of your international\nreferral protocol.\n\nBest regards,\n[Your Name]\n[Phone / Telegram / Email]\n[Website / LinkedIn, if available]', 'clinic', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 'whatsapp_clinic_ru', 'WhatsApp — первый контакт с IPO (русский)', 'whatsapp', 'ru', NULL, 'Добрый день! Меня зовут [Имя], я медицинский координатор для русскоязычных\nпациентов. Хочу обсудить возможность сотрудничества по направлению кейсов\nна второе мнение в [название клиники]. Подскажите, с кем лучше связаться\nпо этому вопросу? Спасибо!', 'clinic', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 'telegram_clinic_ru', 'Telegram — первый контакт с IPO (русский)', 'telegram', 'ru', NULL, 'Добрый день! Меня зовут [Имя], я медицинский координатор для русскоязычных\nпациентов. Хочу обсудить возможность сотрудничества по направлению кейсов\nна второе мнение в [название клиники]. Подскажите, с кем лучше связаться\nпо этому вопросу? Спасибо!', 'clinic', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 'email_translator_ru', 'Письмо переводчику (русский)', 'email', 'ru', 'Предложение о сотрудничестве — медицинский перевод', 'Добрый день, [Имя]!\n\nМеня зовут [Ваше имя], я медицинский координатор — помогаю пациентам\nорганизовать получение второго мнения в зарубежных клиниках.\n\nИщу надёжного партнёра по медицинскому переводу ([языковые пары])\nв направлении [онкология / ортопедия / кардиология].\n\nОбъём работы: периодические кейсы (1–4 в месяц на старте), письменный\nперевод медицинских документов, иногда устное сопровождение онлайн-консультации.\n\nБуду рад(а) обсудить:\n- ваш опыт в медпереводе (ниши, примеры)\n- условия и SLA\n- возможность тестового перевода небольшого фрагмента\n\nУдобно пообщаться в Telegram или по почте — как вам удобнее?\n\nС уважением,\n[Имя], [контакты]', 'translator', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(5, 'email_curator_ru', 'Письмо медицинскому редактору / куратору (русский)', 'email', 'ru', 'Сотрудничество — куратор медицинских кейсов (второе мнение)', 'Добрый день, [Имя Отчество]!\n\nМеня зовут [Ваше имя], занимаюсь координацией получения второго мнения\nдля пациентов в зарубежных клиниках.\n\nИщу врача-консультанта по [специальность] для следующей роли:\n- проверка правильности сборки кейса (хронология, список вопросов)\n- оценка полноты документов перед отправкой в клинику\n- разбор заключения с пациентом «человеческим языком» после консультации\n\nВажно: вы не ставите диагноз пациенту — только помогаете мне как координатору\nструктурировать кейс и правильно интерпретировать ответ клиники.\n\nФормат сотрудничества: разовые кейсы, оплата за кейс-ревью.\nNDA, конфиденциальность — само собой.\n\nЕсли интересно — давайте созвонимся на 20 минут?\n\nС уважением,\n[Имя], [контакты]', 'curator', '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_04_24_194129_create_permission_tables', 1),
(6, '2026_04_24_194942_create_case_statuses_table', 1),
(7, '2026_04_24_195236_create_patients_table', 1),
(8, '2026_04_24_195336_create_medical_cases_table', 1),
(9, '2026_04_24_200000_update_cases_pipeline_service_status', 2),
(10, '2026_04_24_200100_create_case_status_histories_table', 2),
(11, '2026_04_25_100000_create_partner_layers_table', 2),
(12, '2026_04_25_100100_create_niches_table', 2),
(13, '2026_04_25_100200_create_countries_table', 2),
(14, '2026_04_25_100300_create_country_directions_table', 2),
(15, '2026_04_25_100400_create_partners_table', 2),
(16, '2026_04_25_100500_create_niche_partner_table', 2),
(17, '2026_04_25_100600_create_country_partner_table', 2),
(18, '2026_04_25_100700_create_case_partner_table', 2),
(19, '2026_04_25_100800_create_verification_checklists_table', 2),
(20, '2026_04_25_100900_create_verification_checklist_items_table', 2),
(21, '2026_04_25_101000_create_partner_verifications_table', 2),
(22, '2026_04_25_101100_create_partner_verification_items_table', 2),
(23, '2026_04_25_101200_create_message_templates_table', 2),
(24, '2026_04_25_200000_create_partner_research_profiles_table', 2),
(25, '2026_04_26_000100_create_backups_table', 2),
(26, '2026_04_26_000200_create_audit_events_table', 2),
(27, '2026_04_26_000300_create_backup_settings_table', 2),
(28, '2026_04_26_000400_add_progress_to_backups_table', 2),
(29, '2026_04_26_000500_create_jobs_table', 2),
(30, '2026_04_26_000600_add_kind_to_backups_table', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `model_has_permissions`
--

CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `model_has_roles`
--

CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `niches`
--

CREATE TABLE IF NOT EXISTS `niches` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `niches_code_unique` (`code`),
  KEY `niches_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `niches`
--

INSERT INTO `niches` (`id`, `code`, `name`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'oncology', 'Онкология', 'Второе мнение по онкологии, пересмотр гистологии/стадирования. Высокий средний чек, требует аккуратных процессов и сильных партнёров.', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 'neurosurgery_orthopedics', 'Нейрохирургия / Ортопедия', 'Второе мнение по плановым операциям на позвоночнике, суставах, нейрохирургическим вмешательствам.', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 'cardiology', 'Кардиология', 'Второе мнение по выбору тактики лечения, интервенционные вмешательства, оценка рисков.', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 'rare_genetics', 'Редкие диагнозы / Генетика', 'Генетическое тестирование, орфанные заболевания, сложные дифференциальные диагнозы.', 4, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(5, 'reproductive_ivf', 'Репродуктология / ЭКО', 'Второе мнение по стратегии ЭКО, выбор клиники, оценка протокола лечения.', 5, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `niche_partner`
--

CREATE TABLE IF NOT EXISTS `niche_partner` (
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `niche_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`partner_id`,`niche_id`),
  KEY `niche_partner_niche_id_foreign` (`niche_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `niche_partner`
--

INSERT INTO `niche_partner` (`partner_id`, `niche_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `partners`
--

CREATE TABLE IF NOT EXISTS `partners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_layer_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `languages` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_whatsapp` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_telegram` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sla_response_hours` smallint(5) UNSIGNED DEFAULT NULL,
  `sla_result_days` smallint(5) UNSIGNED DEFAULT NULL,
  `pricing_notes` text COLLATE utf8mb4_unicode_ci,
  `invoice_required` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `verification_score` smallint(5) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partners_partner_layer_id_foreign` (`partner_layer_id`),
  KEY `partners_country_id_foreign` (`country_id`),
  KEY `partners_type_status_index` (`type`,`status`),
  KEY `partners_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partners`
--

INSERT INTO `partners` (`id`, `partner_layer_id`, `type`, `name`, `country_id`, `city`, `languages`, `contact_name`, `contact_email`, `contact_phone`, `contact_whatsapp`, `contact_telegram`, `website_url`, `sla_response_hours`, `sla_result_days`, `pricing_notes`, `invoice_required`, `status`, `verification_score`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'clinic', 'Memorial Hospitals Group (Demo)', 1, 'Стамбул', 'EN, RU, TR', 'International Patient Office', 'ipo@memorial-demo.example', NULL, '+90 000 000 0000', NULL, 'https://www.memorial.com.tr/en', 48, 10, 'Официальный счёт от юрлица клиники.', 1, 'new', NULL, 'Демо-запись. Требует верификации перед реальным использованием.', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 2, 'translator', 'Иванова Анна Сергеевна (Demo)', NULL, 'Москва', 'EN↔RU, медицинская тематика', 'Иванова Анна', 'translator.demo@example.com', NULL, NULL, '@translator_demo', NULL, 24, 3, '500 ₽/стр. (стандарт), 800 ₽/стр. (срочно). Устный: 1500 ₽/ч.', 0, 'new', NULL, 'Демо-запись. Требует тестового перевода и подписания NDA.', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 3, 'curator', 'Петров Дмитрий Олегович (Demo)', NULL, 'Санкт-Петербург', 'RU', 'Петров Дмитрий Олегович', 'curator.demo@example.com', NULL, NULL, '@curator_demo', NULL, 24, NULL, '3000 ₽/кейс-ревью.', 0, 'new', NULL, 'Демо-запись. Онколог, к.м.н. Требует проверки на конфликт интересов.', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 4, 'clinic', 'Charité Comprehensive Cancer Center', 3, 'Берлин', NULL, NULL, NULL, '+49 30 450 570', NULL, NULL, 'https://www.charite.de/en/', NULL, NULL, NULL, 0, 'new', NULL, NULL, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `partner_layers`
--

CREATE TABLE IF NOT EXISTS `partner_layers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_layers_code_unique` (`code`),
  KEY `partner_layers_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partner_layers`
--

INSERT INTO `partner_layers` (`id`, `code`, `name`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'clinic_ipo', 'Клиники / International Patient Office (IPO)', 'Принимают пациента, ставят диагноз, оказывают лечение. Предпочтительный контакт — международный отдел (IPO) клиники.', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 'translator', 'Переводчики с медицинской специализацией', 'Письменный перевод медицинских документов; устный перевод на онлайн-консультациях. SLA: 24–72 ч, понятное ценообразование.', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 'curator', 'Медицинский редактор / куратор кейса', 'Врач-консультант, который помогает структурировать вопросы и проверить правильность сборки кейса (без постановки диагноза пациенту напрямую).', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 'imported', 'Импорт (Research)', NULL, 99, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `partner_research_profiles`
--

CREATE TABLE IF NOT EXISTS `partner_research_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `direction` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key_services` json DEFAULT NULL,
  `international_page_url` text COLLATE utf8mb4_unicode_ci,
  `accepts_foreigners_status` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepts_foreigners_source_url` text COLLATE utf8mb4_unicode_ci,
  `accepts_ru_status` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepts_ru_source_url` text COLLATE utf8mb4_unicode_ci,
  `working_hours` text COLLATE utf8mb4_unicode_ci,
  `doctors` json DEFAULT NULL,
  `prices` json DEFAULT NULL,
  `reviews` json DEFAULT NULL,
  `sources` json DEFAULT NULL,
  `last_checked_at` date DEFAULT NULL,
  `source_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_clinic_yaml` longtext COLLATE utf8mb4_unicode_ci,
  `review_markdown` longtext COLLATE utf8mb4_unicode_ci,
  `imported_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_research_profiles_partner_id_unique` (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partner_research_profiles`
--

INSERT INTO `partner_research_profiles` (`id`, `partner_id`, `address`, `direction`, `key_services`, `international_page_url`, `accepts_foreigners_status`, `accepts_foreigners_source_url`, `accepts_ru_status`, `accepts_ru_source_url`, `working_hours`, `doctors`, `prices`, `reviews`, `sources`, `last_checked_at`, `source_path`, `raw_clinic_yaml`, `review_markdown`, `imported_at`, `created_at`, `updated_at`) VALUES
(1, 4, 'Charitéplatz 1, 10117 Berlin, Deutschland', 'Онкология', '[\"Онкология\", \"Гематология\", \"Радиотерапия\", \"Химиотерапия\", \"Иммунотерапия\"]', 'https://www.charite.de/en/patients_visitors/international_patients/', 'да', 'https://www.charite.de/en/patients_visitors/international_patients/', 'да', 'https://www.charite.de/en/patients_visitors/international_patients/', 'Пн–Пт 08:00–18:00', NULL, NULL, NULL, '[{\"url\": \"https://www.charite.de/en/patients_visitors/international_patients/\", \"описание\": \"Официальный сайт — страница для иностранных пациентов\", \"дата_проверки\": \"2026-04-01\"}]', '2026-04-01', 'sample/charite/clinic.yaml', 'название: Charité Comprehensive Cancer Center\nстрана: Германия\nгород: Берлин\nнаправление: Онкология\nадрес: Charitéplatz 1, 10117 Berlin, Deutschland\n\nконтакты:\n  сайт: https://www.charite.de/en/\n  страница_для_иностранных_пациентов: https://www.charite.de/en/patients_visitors/international_patients/\n  телефон: +49 30 450 570\n\nприём_иностранцев:\n  статус: да\n  источник_url: https://www.charite.de/en/patients_visitors/international_patients/\n\nприём_пациентов_из_РФ:\n  статус: да\n  источник_url: https://www.charite.de/en/patients_visitors/international_patients/\n\nрежим_работы:\n  клиника: \"Пн–Пт 08:00–18:00\"\n\nключевые_услуги:\n  - Онкология\n  - Гематология\n  - Радиотерапия\n  - Химиотерапия\n  - Иммунотерапия\n\nдата_последней_проверки: \"2026-04-01\"\n\nисточники:\n  - url: https://www.charite.de/en/patients_visitors/international_patients/\n    описание: Официальный сайт — страница для иностранных пациентов\n    дата_проверки: \"2026-04-01\"\n', '# Charité Comprehensive Cancer Center — Берлин\n\n**Charité** — один из крупнейших университетских медицинских центров в Европе, расположенный в Берлине, Германия. Онкологический центр Charité (CCCC) занимает лидирующие позиции в лечении онкологических заболеваний.\n\n## Специализации\n\n- **Гематоонкология**: лейкемии, лимфомы, миелома\n- **Онкохирургия**: минимально инвазивные вмешательства, роботическая хирургия\n- **Радиотерапия**: стереотаксическая радиохирургия, протонная терапия\n- **Иммунотерапия**: CAR-T-клеточная терапия, иммунные контрольные точки\n\n## Опыт работы с русскоязычными пациентами\n\nКлиника имеет многолетний опыт приёма пациентов из России и СНГ. Доступна помощь с переводом документации и сопровождением.\n\n## Процедура приёма\n\n1. Направление онкологических документов по электронной почте\n2. Рассмотрение заявки в течение 5–7 рабочих дней\n3. Составление индивидуального плана лечения\n4. Оформление приглашения для визы (при необходимости)\n\n## Источники\n\n- [Официальный сайт](https://www.charite.de/en/)\n- [Страница для иностранных пациентов](https://www.charite.de/en/patients_visitors/international_patients/)\n', '2026-04-29 07:35:48', '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `partner_verifications`
--

CREATE TABLE IF NOT EXISTS `partner_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `checklist_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_verifications_partner_id_checklist_id_unique` (`partner_id`,`checklist_id`),
  KEY `partner_verifications_checklist_id_foreign` (`checklist_id`),
  KEY `partner_verifications_verified_by_user_id_foreign` (`verified_by_user_id`),
  KEY `partner_verifications_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partner_verifications`
--

INSERT INTO `partner_verifications` (`id`, `partner_id`, `checklist_id`, `status`, `verified_at`, `verified_by_user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'not_started', NULL, NULL, NULL, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 2, 2, 'not_started', NULL, NULL, NULL, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 3, 3, 'not_started', NULL, NULL, NULL, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `partner_verification_items`
--

CREATE TABLE IF NOT EXISTS `partner_verification_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_verification_id` bigint(20) UNSIGNED NOT NULL,
  `checklist_item_id` bigint(20) UNSIGNED NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  `checked_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pvi_verif_item_unique` (`partner_verification_id`,`checklist_item_id`),
  KEY `partner_verification_items_checklist_item_id_foreign` (`checklist_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patients_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 'manager', 'web', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 'coordinator', 'web', '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 'intake', 'web', '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `role_has_permissions`
--

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@medical07.local', NULL, '$2y$12$DZgPCuCiv0NAO6KZ4MeOVuEAT2roGDu49IiUYHhHLWuT5uodXgAnq', NULL, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `verification_checklists`
--

CREATE TABLE IF NOT EXISTS `verification_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `partner_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verification_checklists_code_unique` (`code`),
  KEY `verification_checklists_partner_type_index` (`partner_type`),
  KEY `verification_checklists_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `verification_checklists`
--

INSERT INTO `verification_checklists` (`id`, `code`, `name`, `partner_type`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'clinic_verification', 'Верификация клиники / IPO', 'clinic', 'Чек-лист для проверки клиники или International Patient Office перед началом сотрудничества.', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 'translator_verification', 'Верификация переводчика', 'translator', 'Чек-лист для проверки переводчика с медицинской специализацией.', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 'curator_verification', 'Верификация медицинского редактора / куратора', 'curator', 'Чек-лист для проверки врача-куратора кейса.', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

-- --------------------------------------------------------

--
-- Структура таблицы `verification_checklist_items`
--

CREATE TABLE IF NOT EXISTS `verification_checklist_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `checklist_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verification_checklist_items_checklist_id_code_unique` (`checklist_id`,`code`),
  KEY `verification_checklist_items_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `verification_checklist_items`
--

INSERT INTO `verification_checklist_items` (`id`, `checklist_id`, `code`, `text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'license_checked', 'Лицензия учреждения проверена на сайте регулятора страны (или реестр аккредитации JCI/ISO).', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(2, 1, 'ipo_is_staff', 'IPO-контакт является сотрудником клиники (не посредником).', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(3, 1, 'official_invoices', 'Счета выставляются официально от юрлица клиники (не наличными и не «на карту»).', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(4, 1, 'test_case_passed', 'Пройден тестовый кейс: ответ получен в согласованный срок, заключение содержательное.', 4, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(5, 1, 'sla_agreed', 'Согласован SLA: первичный ответ ≤ 48 ч, заключение ≤ 10 рабочих дней.', 5, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(6, 1, 'coordinator_terms', 'Получены условия работы с координаторами (комиссионная политика или её отсутствие).', 6, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(7, 2, 'medical_samples', 'Есть примеры медицинских переводов (аналогичная ниша).', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(8, 2, 'test_translation', 'Тестовый фрагмент (1–2 страницы) переведён без грубых ошибок в терминологии.', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(9, 2, 'sla_in_writing', 'SLA зафиксирован письменно (мессенджер/email).', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(10, 2, 'nda_signed', 'Подписано или согласовано NDA (или согласие на конфиденциальность в простой форме).', 4, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(11, 2, 'pricing_formula', 'Стоимость зафиксирована с формулой (страница/символ/час).', 5, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(12, 3, 'specialization_match', 'Специализация соответствует нише.', 1, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(13, 3, 'no_conflict_of_interest', 'Нет конфликта интересов (не аффилирован с клиниками из вашей сети без раскрытия).', 2, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(14, 3, 'role_understood', 'Чётко понимает свою роль: структурирование, а не постановка диагноза пациенту напрямую.', 3, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(15, 3, 'sla_agreed', 'Готов работать в согласованном SLA (≤ 24–48 ч на review кейса).', 4, '2026-04-29 07:35:48', '2026-04-29 07:35:48'),
(16, 3, 'nda_signed', 'Подписано NDA.', 5, '2026-04-29 07:35:48', '2026-04-29 07:35:48');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `audit_events`
--
ALTER TABLE `audit_events`
  ADD CONSTRAINT `audit_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `backups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_assigned_to_user_id_foreign` FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cases_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_pipeline_status_id_foreign` FOREIGN KEY (`pipeline_status_id`) REFERENCES `case_statuses` (`id`),
  ADD CONSTRAINT `cases_service_status_id_foreign` FOREIGN KEY (`service_status_id`) REFERENCES `case_statuses` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `case_partner`
--
ALTER TABLE `case_partner`
  ADD CONSTRAINT `case_partner_medical_case_id_foreign` FOREIGN KEY (`medical_case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_partner_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `case_status_histories`
--
ALTER TABLE `case_status_histories`
  ADD CONSTRAINT `case_status_histories_changed_by_user_id_foreign` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `case_status_histories_from_pipeline_status_id_foreign` FOREIGN KEY (`from_pipeline_status_id`) REFERENCES `case_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `case_status_histories_from_service_status_id_foreign` FOREIGN KEY (`from_service_status_id`) REFERENCES `case_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `case_status_histories_medical_case_id_foreign` FOREIGN KEY (`medical_case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_status_histories_to_pipeline_status_id_foreign` FOREIGN KEY (`to_pipeline_status_id`) REFERENCES `case_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `case_status_histories_to_service_status_id_foreign` FOREIGN KEY (`to_service_status_id`) REFERENCES `case_statuses` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `country_directions`
--
ALTER TABLE `country_directions`
  ADD CONSTRAINT `country_directions_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `country_directions_niche_id_foreign` FOREIGN KEY (`niche_id`) REFERENCES `niches` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `country_partner`
--
ALTER TABLE `country_partner`
  ADD CONSTRAINT `country_partner_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `country_partner_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `niche_partner`
--
ALTER TABLE `niche_partner`
  ADD CONSTRAINT `niche_partner_niche_id_foreign` FOREIGN KEY (`niche_id`) REFERENCES `niches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `niche_partner_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `partners_partner_layer_id_foreign` FOREIGN KEY (`partner_layer_id`) REFERENCES `partner_layers` (`id`);

--
-- Ограничения внешнего ключа таблицы `partner_research_profiles`
--
ALTER TABLE `partner_research_profiles`
  ADD CONSTRAINT `partner_research_profiles_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `partner_verifications`
--
ALTER TABLE `partner_verifications`
  ADD CONSTRAINT `partner_verifications_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `verification_checklists` (`id`),
  ADD CONSTRAINT `partner_verifications_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partner_verifications_verified_by_user_id_foreign` FOREIGN KEY (`verified_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `partner_verification_items`
--
ALTER TABLE `partner_verification_items`
  ADD CONSTRAINT `partner_verification_items_checklist_item_id_foreign` FOREIGN KEY (`checklist_item_id`) REFERENCES `verification_checklist_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partner_verification_items_partner_verification_id_foreign` FOREIGN KEY (`partner_verification_id`) REFERENCES `partner_verifications` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `verification_checklist_items`
--
ALTER TABLE `verification_checklist_items`
  ADD CONSTRAINT `verification_checklist_items_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `verification_checklists` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
