# RAF HRIS Backend

Laravel 12 backend baseline for RAF HRIS.

## Stack

- PHP 8.2+
- Laravel 12
- PostgreSQL
- Laravel Sanctum (API token auth)
- Spatie Laravel Permission (RBAC)
- iamfarhad/laravel-audit-log (audit)

## Quick Start

1. Install dependencies:

```bash
composer install
```

2. Configure environment:

```bash
cp .env.example .env
```

3. Set PostgreSQL values in `.env`:

- `DB_CONNECTION=pgsql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=5432`
- `DB_DATABASE=raf_hris`
- `DB_USERNAME=postgres`
- `DB_PASSWORD=postgres`

4. Generate app key and migrate/seed:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

5. Start server:

```bash
php artisan serve
```

## Initial API Endpoints

- `GET /api/v1/health`
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout` (Sanctum)
- `GET /api/v1/auth/me` (Sanctum)
- `POST /api/v1/auth/refresh` (Sanctum)

## Seeded Admin

- Email: `admin@raf.local`
- Password: `password`
