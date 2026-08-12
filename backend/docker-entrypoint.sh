#!/bin/sh
set -e

php artisan config:clear
php artisan migrate --force
php artisan storage:link || true

exec php -S 0.0.0.0:${PORT:-8080} -t public