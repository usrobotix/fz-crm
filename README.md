# FZ-CRM — Compliance Documentation CRM

MVP CRM для создания и управления пакетами документов по ФЗ-152 и другим нормативным актам.

## Стек

- **Laravel 10** (PHP 8.2+)
- **MySQL 8** (или SQLite для разработки)
- **Tailwind CSS** + **Alpine.js** (Vite build)
- **spatie/laravel-permission** — роли: `admin`, `editor`, `reviewer`
- **Laravel Breeze** — аутентификация

---

## Быстрый старт (локально)

### 1. Клонировать и установить зависимости

```bash
git clone https://github.com/usrobotix/fz-crm.git fz-crm
cd fz-crm/crm
composer install
npm install
```

### 2. Создать базу данных (MySQL)

```sql
CREATE DATABASE fz_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Настроить окружение

```bash
cp .env.example .env
php artisan key:generate
```

Отредактировать `.env`:

```env
APP_NAME=FZ-CRM
APP_URL=http://fz-crm.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fz_crm
DB_USERNAME=root
DB_PASSWORD=secret

QUEUE_CONNECTION=database   # или redis — для фоновых задач
```

### 4. Миграции и первоначальные данные

```bash
php artisan migrate
php artisan db:seed
```

После сидирования создаётся пользователь-администратор:
- **Email:** `admin@fz-crm.local`
- **Пароль:** `ChangeMe123!`

> ⚠️ Сразу смените пароль в профиле после первого входа.

### 5. Собрать фронтенд

```bash
npm run build
# или для разработки:
npm run dev
```

### 6. Настройте DocumentRoot

Для OSPanel / nginx направьте DocumentRoot на `crm/public`.

---

## Очередь задач (queue worker)

Фоновое создание и восстановление резервных копий требует работающего воркера очереди.

```bash
# Запуск воркера
php artisan queue:work --sleep=3 --tries=3 --timeout=600

# Или как демон (supervisord / systemd)
php artisan queue:work --daemon
```

> Если `QUEUE_CONNECTION=sync` (значение по умолчанию), задачи выполняются синхронно в текущем запросе — удобно для разработки, но не для продакшна.

---

## Резервное копирование и восстановление

### Создать резервную копию

1. Войти как admin.
2. Перейти в **⚙️ Технический раздел** → **Резервные копии**.
3. Выбрать тип (`БД` / `Файлы` / `Полная`) и нажать **Создать**.
4. Статус обновляется автоматически (polling каждые 3 сек).

Резервные копии сохраняются в `storage/app/backups/`.

### Восстановить БД из резервной копии

1. На странице резервных копий найдите нужную запись со статусом **Готово** и типом **db** или **full**.
2. Нажмите **Восстановить** (кнопка появляется только для DB-бэкапов).
3. Подтвердите операцию.
4. Прогресс отслеживается через файловую систему (`storage/app/restore-progress/{uuid}.json`) — это гарантирует работу эндпоинта даже если таблица `backups` была очищена при восстановлении.

Эндпоинт статуса: `GET /admin/technical/backups/restore/{uuid}/status`
- `200` — прогресс доступен
- `410` — файл удалён (восстановление завершено)

---

## Импорт шаблонов из репозитория fz152

Команда `fz152:import` читает `STATUS.md` репозитория [usrobotix/fz152](https://github.com/usrobotix/fz152) и импортирует строки с категорией `Library` как шаблоны документов.

### Запуск

```bash
# Предварительный просмотр (без записи в БД)
php artisan fz152:import --dry-run

# Реальный импорт
php artisan fz152:import

# С GitHub-токеном (для приватных репо или обхода rate limit)
php artisan fz152:import --token=ghp_yourtoken

# Или через переменную окружения
GITHUB_TOKEN=ghp_yourtoken php artisan fz152:import
```

**Идемпотентность:** команда использует поле `repo_path` как уникальный ключ — повторный запуск пропустит уже импортированные документы.

### Что импортируется

- Создаётся закон `fz152` ("ФЗ-152 о персональных данных"), если не существует.
- Для каждой строки таблицы с категорией `Library`:
  - Создаётся запись `Template`
  - Загружается содержимое `.md` файла и сохраняется как первая `TemplateVersion`

---

## Роли и разрешения

| Роль       | Доступ |
|------------|--------|
| `admin`    | Полный доступ, технический раздел, управление бэкапами |
| `editor`   | CRUD для законов, шаблонов, компаний, проектов, документов |
| `reviewer` | Просмотр, смена статуса документов |

Роли управляются через `spatie/laravel-permission`. Назначать роли пользователям можно через `php artisan tinker`:

```php
$user = App\Models\User::where('email', 'user@example.com')->first();
$user->assignRole('editor');
```

---

## Структура проекта

```
crm/
├── app/
│   ├── Console/Commands/ImportFz152Templates.php   # Импорт шаблонов
│   ├── Http/Controllers/
│   │   ├── Admin/BackupController.php              # Бэкапы
│   │   ├── DashboardController.php
│   │   ├── LawController.php
│   │   ├── CompanyController.php
│   │   ├── ProjectController.php
│   │   ├── TemplateController.php
│   │   ├── DocumentController.php
│   │   └── ExportController.php                   # ZIP-экспорт проекта
│   ├── Jobs/
│   │   ├── RunBackupJob.php
│   │   └── RestoreDatabaseFromBackupJob.php
│   ├── Models/
│   │   ├── Law.php / Template.php / TemplateVersion.php
│   │   ├── Company.php / Project.php
│   │   ├── Document.php / DocumentVersion.php
│   │   └── Backup.php / AuditEvent.php
│   └── Services/RestoreProgressService.php
├── database/
│   ├── migrations/                                 # 14 миграций
│   └── seeders/RbacSeeder.php
└── resources/views/
    ├── layouts/          # app.blade.php, navigation.blade.php
    ├── dashboard.blade.php
    ├── laws/             # index, show, create, edit
    ├── templates/        # index, show, create, edit (Markdown + preview)
    ├── companies/        # index, show, create, edit
    ├── projects/         # index, show, create, edit + export
    ├── documents/        # index, show, create, edit (Markdown + preview)
    └── admin/technical/backups/index.blade.php
```

---

## Экспорт проекта в ZIP

На странице проекта нажмите кнопку **Экспортировать ZIP**. В архиве:
- Каждый документ сохраняется как `.md` файл
- `INDEX.md` — сводная таблица всех документов с текущими статусами (аналог `STATUS.md` в fz152)
