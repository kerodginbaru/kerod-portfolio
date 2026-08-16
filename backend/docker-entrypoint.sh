#!/bin/sh
set -e

php artisan config:clear
php artisan migrate --force
php artisan storage:link || true

# Only seeds on the very first boot (empty database) — never overwrites
# admin edits made through the dashboard on later restarts/redeploys.
php artisan db:seed-if-empty || true

# Free-tier Render has no Shell access, so the admin account is created
# here instead — set ADMIN_EMAIL + ADMIN_PASSWORD (and optionally
# ADMIN_NAME) as environment variables in Render. This fails harmlessly
# on later boots once the account already exists (unique email).
if [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
    php artisan admin:create --name="${ADMIN_NAME:-Kerod Ginbaru}" --email="$ADMIN_EMAIL" --password="$ADMIN_PASSWORD" || true
fi

exec php -S 0.0.0.0:${PORT:-8080} -t public