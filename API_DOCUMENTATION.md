# API Documentation

Base URL: `{APP_URL}/api` (e.g. `http://localhost:8000/api`)

Every response follows:

```json
{ "success": true, "data": {}, "message": "..." }
```

```json
{ "success": false, "message": "...", "errors": { "field": ["..."] } }
```

Admin endpoints require `Authorization: Bearer <token>` from `POST /admin/login`.

---

## Public endpoints

| Method | Path | Notes |
|---|---|---|
| GET | `/projects` | Paginated, published only. Query: `status`, `category`, `technology`, `per_page` |
| GET | `/projects/featured` | Featured, published projects |
| GET | `/projects/{slug}` | 404 if archived or missing |
| GET | `/technologies` | All technologies, alphabetical |
| GET | `/services` | All services, by sort order |
| GET | `/skills` | Skill categories with nested skills |
| GET | `/experience` | Work experience, most recent first |
| GET | `/education` | Education history, most recent first |
| GET | `/blog` | Published posts only, paginated |
| GET | `/blog/{slug}` | 404 unless published |
| GET | `/testimonials` | Published testimonials only |
| GET | `/social-links` | Enabled links only |
| GET | `/site-settings` | Flat settings object |
| POST | `/contact` | Rate limited 5/min/IP — see below |

### `POST /contact`

```json
{
  "name": "Jane Visitor",
  "email": "jane@example.com",
  "phone": "0911223344",
  "subject": "Freelance project inquiry",
  "message": "..."
}
```

Validation: `name` required, `email` required+valid, `subject` required,
`message` required min 10 chars. Includes a hidden honeypot field
(`website`) — real users never populate it; bots that do get silently
rejected.

---

## Admin authentication

| Method | Path | Auth |
|---|---|---|
| POST | `/admin/login` | none (rate limited 10/min/IP) |
| POST | `/admin/logout` | Bearer token |
| GET | `/admin/me` | Bearer token |
| PUT | `/admin/password` | Bearer token |

There is **no** `/admin/register` endpoint. Admin accounts are created only
via `php artisan admin:create`.

`POST /admin/login`:

```json
{ "email": "you@example.com", "password": "..." }
```

Returns `{ data: { user, token } }`. Send `token` as
`Authorization: Bearer <token>` on every subsequent admin request.

---

## Admin endpoints (all require Bearer auth)

| Method | Path |
|---|---|
| GET | `/admin/dashboard` |
| GET/POST | `/admin/projects` |
| GET/PUT/DELETE | `/admin/projects/{id}` |
| POST | `/admin/projects/{id}/restore` |
| PATCH | `/admin/projects/{id}/toggle-featured` |
| POST | `/admin/projects/reorder` |
| POST | `/admin/projects/{id}/images` (multipart, field `image`) |
| DELETE | `/admin/projects/{id}/images/{imageId}` |
| PATCH | `/admin/projects/{id}/images/{imageId}/cover` |
| POST | `/admin/projects/{id}/images/reorder` |
| GET/POST/PUT/DELETE | `/admin/project-categories` |
| GET/POST/PUT/DELETE | `/admin/technologies` |
| GET/POST/PUT/DELETE | `/admin/services` |
| GET/POST/DELETE | `/admin/skill-categories` |
| POST/PUT/DELETE | `/admin/skills` |
| GET/POST/PUT/DELETE | `/admin/experience` |
| GET/POST/PUT/DELETE | `/admin/education` |
| GET/POST/PUT/DELETE | `/admin/blog` |
| GET/POST/PUT/DELETE | `/admin/testimonials` |
| GET/POST/PUT/DELETE | `/admin/social-links` |
| POST | `/admin/social-links/reorder` |
| GET/PUT | `/admin/site-settings` |
| GET/PUT/DELETE | `/admin/messages` |

### Image upload (`POST /admin/projects/{id}/images`)

`multipart/form-data`: `image` (jpeg/png/webp, max 4MB), `alt_text`
(optional), `caption` (optional), `is_cover` (optional boolean). The
uploaded filename is never trusted or stored — the server generates a UUID
filename and returns a public `url` in the response.

### Site settings (`PUT /admin/site-settings`)

```json
{ "settings": { "hero_heading": "New heading", "phone": "+251..." } }
```

Any key from the `site_settings` table can be updated; unknown keys are
simply created.

---

## Status codes

| Code | Meaning |
|---|---|
| 200 | Success |
| 201 | Created |
| 401 | Unauthenticated (missing/invalid token) |
| 403 | Authenticated but not authorized |
| 404 | Not found |
| 422 | Validation failed — see `errors` |
| 429 | Rate limited |
