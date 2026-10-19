#!/bin/sh
set -e
cd /var/www/html

# Render tells the container which port to listen on.
PORT="${PORT:-8080}"
sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

[ -f .env ] || cp .env.example .env
[ -n "$APP_KEY" ] || php artisan key:generate --force
export APP_URL="${RENDER_EXTERNAL_URL:-${APP_URL:-http://localhost:${PORT}}}"

# Every start gets fresh sample data. The free plan sleeps when idle, so this also resets after quiet periods.
touch database/database.sqlite
php artisan demo:reset
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache database

exec apache2-foreground
