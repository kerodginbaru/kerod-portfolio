# Kerod Ginbaru — Full-Stack Developer Portfolio

Full-stack & mobile developer portfolio for freelance, contract, and
business software work. Decoupled architecture: a Laravel REST API is the
only layer that talks to MySQL; Next.js consumes it over JSON and never
touches the database directly.

```
kerod-portfolio/
├── frontend/     Next.js + TypeScript + Tailwind — public site
├── backend/      Laravel REST API + admin panel API
├── docs/         (reserved for extra architecture notes)
├── API_DOCUMENTATION.md
└── README.md     (this file)
```

## Quick start

```bash
# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan admin:create
php artisan serve            # http://localhost:8000

# Frontend (separate terminal)
cd frontend
npm install
cp .env.example .env.local   # set NEXT_PUBLIC_API_URL=http://localhost:8000/api
npm run dev                  # http://localhost:3000
```

The frontend works even before the backend is running or seeded — every
page falls back to real demo data in `frontend/lib/fallback-data.ts` until
`NEXT_PUBLIC_API_URL` responds.

See `frontend/README.md` and `backend/README.md` for full setup detail,
and `API_DOCUMENTATION.md` for every endpoint.

## Design

Dark, ink-black canvas with a muted antique-gold accent (a deliberate nod
to Ge'ez manuscript illumination rather than a generic "AI dark mode"
palette), oversized display type, a recurring "trace line" motif that
threads through the hero and section headers, and a scroll-revealed
Business + Technology process diagram that's core to how Kerod positions
himself — a developer who understands the business process behind the
code, not just the code.

## What's real vs. placeholder

- **Real:** every project listed (Yadot, HOPE, EthioShop, EOTC-RCAMS,
  Document Management System, Digital Restaurant Menu, BiblioVerse, HOPE
  System, and the four planned/future projects), every skill and service,
  contact details.
- **Placeholder, by design:** `github_url` / `live_url` on each project
  (add real links via the admin panel as they become available),
  testimonials (empty until real ones are entered — never fabricated),
  and social link URLs (update to the real profiles in
  `backend/database/seeders/SocialLinkSeeder.php` or via the admin panel).

## Status of this build

This repository was generated end-to-end in a single working session.

- **Frontend**: fully scaffolded, builds and lints clean (`npm run build`,
  `npm run lint` both verified in this repo).
- **Backend**: fully written to standard Laravel 11 conventions
  (migrations, models, policies, Form Requests, API Resources,
  controllers, routes, seeders, factories, Feature tests) but could not be
  executed in the sandbox this was built in (no Packagist access to run
  `composer install`). Run the commands above on a machine with normal
  internet access to install dependencies and verify with `php artisan
  test`.

Continue development directly in each app's directory — see the two
sub-READMEs for day-to-day workflow, and treat the admin panel (still to
be built as a Next.js `/admin` section consuming the endpoints in
`API_DOCUMENTATION.md`) as the next milestone for managing content without
touching frontend source code.

## License

MIT — see `LICENSE`.
