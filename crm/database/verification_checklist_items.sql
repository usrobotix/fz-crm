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
-- Структура таблицы `verification_checklist_items`
--

CREATE TABLE `verification_checklist_items` (
  `id` bigint UNSIGNED NOT NULL,
  `checklist_id` bigint UNSIGNED NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `verification_checklist_items`
--

INSERT INTO `verification_checklist_items` (`id`, `checklist_id`, `code`, `text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'license_checked', 'Лицензия учреждения проверена на сайте регулятора страны (или реестр аккредитации JCI/ISO).', 1, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(2, 1, 'ipo_is_staff', 'IPO-контакт является сотрудником клиники (не посредником).', 2, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(3, 1, 'official_invoices', 'Счета выставляются официально от юрлица клиники (не наличными и не «на карту»).', 3, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(4, 1, 'test_case_passed', 'Пройден тестовый кейс: ответ получен в согласованный срок, заключение содержательное.', 4, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(5, 1, 'sla_agreed', 'Согласован SLA: первичный ответ ≤ 48 ч, заключение ≤ 10 рабочих дней.', 5, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(6, 1, 'coordinator_terms', 'Получены условия работы с координаторами (комиссионная политика или её отсутствие).', 6, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(7, 2, 'medical_samples', 'Есть примеры медицинских переводов (аналогичная ниша).', 1, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(8, 2, 'test_translation', 'Тестовый фрагмент (1–2 страницы) переведён без грубых ошибок в терминологии.', 2, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(9, 2, 'sla_in_writing', 'SLA зафиксирован письменно (мессенджер/email).', 3, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(10, 2, 'nda_signed', 'Подписано или согласовано NDA (или согласие на конфиденциальность в простой форме).', 4, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(11, 2, 'pricing_formula', 'Стоимость зафиксирована с формулой (страница/символ/час).', 5, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(12, 3, 'specialization_match', 'Специализация соответствует нише.', 1, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(13, 3, 'no_conflict_of_interest', 'Нет конфликта интересов (не аффилирован с клиниками из вашей сети без раскрытия).', 2, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(14, 3, 'role_understood', 'Чётко понимает свою роль: структурирование, а не постановка диагноза пациенту напрямую.', 3, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(15, 3, 'sla_agreed', 'Готов работать в согласованном SLA (≤ 24–48 ч на review кейса).', 4, '2026-04-25 13:06:22', '2026-04-25 13:06:22'),
(16, 3, 'nda_signed', 'Подписано NDA.', 5, '2026-04-25 13:06:22', '2026-04-25 13:06:22');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `verification_checklist_items`
--
ALTER TABLE `verification_checklist_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `verification_checklist_items_checklist_id_code_unique` (`checklist_id`,`code`),
  ADD KEY `verification_checklist_items_sort_order_index` (`sort_order`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `verification_checklist_items`
--
ALTER TABLE `verification_checklist_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `verification_checklist_items`
--
ALTER TABLE `verification_checklist_items`
  ADD CONSTRAINT `verification_checklist_items_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `verification_checklists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
