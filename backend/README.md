# Backend — Laravel REST API

Powers the public portfolio site and the `/admin` dashboard. Pure JSON API,
no server-rendered views.

## Stack

- Laravel 11, PHP 8.2+
- MySQL
- Laravel Sanctum (bearer-token auth for the admin panel)
- Laravel API Resources, Form Requests, Policies

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` — at minimum set `DB_*` to a real MySQL database, and
`FRONTEND_URL` / `SANCTUM_STATEFUL_DOMAINS` to match where the Next.js app
runs (`http://localhost:3000` in local dev).

```bash
php artisan migrate
php artisan storage:link          # exposes storage/app/public at /storage
php artisan db:seed               # real project/skill/service data — see below
php artisan admin:create          # the only way to create an admin account
```

`admin:create` prompts for a name, email, and password (min 10 chars, mixed
case + numbers). There is intentionally no public `/register` endpoint —
this backend has exactly one admin.

Run the dev server:

```bash
php artisan serve
```

The API is now at `http://localhost:8000/api`.

## Seed data

`database/seeders/` contains **only real content** from the portfolio brief:
actual project names, real tech stacks, real service descriptions. It does
**not** seed testimonials — those must be entered through the admin panel
once they exist, per the "no fake testimonials" requirement. Projects like
HOPE and EthioShop are seeded with `status = in_development`; the future
academic projects are seeded `planned`. Update `github_url` / `live_url` on
each project via the admin panel as they become available.

## Tests

```bash
php artisan test
```

`tests/Feature` covers: public project visibility/filtering, admin project
CRUD + soft-delete/restore, admin auth (login, no-registration, password
change), and the contact form (validation, IP hashing, honeypot, rate
limiting). Tests run against an in-memory SQLite database — no MySQL
required for CI.

> **Note on this build's origin:** this codebase was generated in a sandboxed
> environment without access to Packagist, so `composer install` and
> `php artisan test` could not be executed to verify it end-to-end. The code
> follows standard Laravel 11 conventions throughout; run the commands above
> on your machine and open an issue/fix as needed if anything doesn't line up
> with your exact Laravel/PHP patch version.

## API response shape

Every endpoint returns:

```json
{ "success": true, "data": { }, "message": "..." }
```

or, on error:

```json
{ "success": false, "message": "...", "errors": { } }
```

See `../API_DOCUMENTATION.md` for the full endpoint list.

## Security notes

- Admin auth uses Sanctum personal access tokens (`Authorization: Bearer <token>`),
  not cookies — works cleanly with a decoupled Next.js frontend on a
  different origin.
- Contact form: rate limited to 5 submissions/minute/IP, honeypot field,
  IP stored only as a SHA-256 hash (never raw).
- Image uploads: MIME + extension + size validated, filenames are
  server-generated UUIDs (client filenames are never trusted or stored),
  files live only on the `public` disk.
- CORS is locked to `FRONTEND_URL` in `config/cors.php` — update it before
  deploying to production.
- `.env` is never committed; `.env.example` documents every variable.

## Deployment

Any standard Laravel host works (a VPS with PHP-FPM + Nginx, Forge,
Laravel Cloud, etc.). Checklist:

1. Set `APP_ENV=production`, `APP_DEBUG=false`.
2. Set real `DB_*`, `FRONTEND_URL`, `SANCTUM_STATEFUL_DOMAINS`, `APP_URL`.
3. `composer install --no-dev --optimize-autoloader`
4. `php artisan migrate --force`
5. `php artisan storage:link`
6. `php artisan config:cache && php artisan route:cache`
7. Point the frontend's `NEXT_PUBLIC_API_URL` at this API's `/api` path.
