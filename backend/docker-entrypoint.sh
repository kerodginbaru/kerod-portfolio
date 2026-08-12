#!/bin/sh
set -e

php artisan config:clear
php artisan migrate --force
php artisan storage:link || true

# Seeders use updateOrCreate throughout, so running this on every boot is
# safe and just keeps real project/skill/service data in sync.
php artisan db:seed --force || true

# Free-tier Render has no Shell access, so the admin account is created
# here instead — set ADMIN_EMAIL + ADMIN_PASSWORD (and optionally
# ADMIN_NAME) as environment variables in Render. This fails harmlessly
# on later boots once the account already exists (unique email).
if [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
    php artisan admin:create --name="${ADMIN_NAME:-Kerod Ginbaru}" --email="$ADMIN_EMAIL" --password="$ADMIN_PASSWORD" || true
fi

exec php -S 0.0.0.0:${PORT:-8080} -t public