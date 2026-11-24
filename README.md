# QR Attendance System

QR Attendance System is a Laravel application for managing classes, student enrollment, QR-based check-ins, attendance records, schedules, and role-based administration.

## Features

- Separate dashboards for administrators, professors, and students
- Class and student management
- Personal QR codes for student attendance
- Attendance recording and report views
- Class schedules and professor assignments
- Role-based authorization and account settings
- PostgreSQL/Supabase and SQLite database support

## Technology

- PHP 8.2+
- Laravel 12
- PostgreSQL or SQLite
- Vite 7
- Tailwind CSS 4
- PHP GD extension for QR image generation

## Quick start

### Windows / Laragon / XAMPP

1. Install PHP 8.2+, Composer, Node.js 20+, and a database driver.
2. Create the environment file:
   ```powershell
   Copy-Item .env.example .env
   ```
3. Create and configure SQLite:
   ```powershell
   New-Item -ItemType File database/database.sqlite -Force
   php artisan key:generate
   php artisan migrate --seed
   ```
4. Install dependencies and build assets:
   ```powershell
   composer install
   npm install
   npm run build
   ```
5. Start the application:
   ```powershell
   php artisan serve
   ```

Open `http://127.0.0.1:8000`.

### PostgreSQL or Supabase

Set `DB_CONNECTION=pgsql` and provide `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_SSLMODE` in `.env`, then run:

```powershell
php artisan config:clear
php artisan migrate --seed
```

For Supabase, use the Session Pooler credentials supplied by the project owner.

## Development

Run the application and frontend watcher:

```powershell
composer run dev
```

Run tests:

```powershell
php artisan test
```

Build production assets:

```powershell
npm run build
```

## Demo accounts

The database seeder creates development accounts. Change or remove these credentials before deploying to any shared or production environment.

| Role | Email | Password |
|---|---|---|
| Administrator | `admin@example.com` | `admin123` |
| Professor | `steven@gmail.com` | `steven123` |
| Student | `student@example.com` | `student123` |

## Project structure

- `app/` - models, controllers, middleware, and console commands
- `routes/` - web, API, and role-specific routes
- `resources/views/` - Blade interfaces
- `resources/js/` - JavaScript entry points
- `database/migrations/` - database schema
- `database/seeders/` - development seed data
- `tests/` - PHPUnit application tests

## Security

- Never commit `.env`, database credentials, or production backups.
- Change all demo passwords and remove demo accounts before deployment.
- Use strong `APP_KEY` and database secrets.
- Run behind HTTPS in production.
