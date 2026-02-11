#!/usr/bin/env bash
set -e

cd /var/www/html

# Replace nginx port dynamically for Railway
sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/sites-available/default

# Wait for DB (optional but recommended)
echo "Waiting for database..."
for i in {1..20}; do
  php artisan migrate:status >/dev/null 2>&1 && break
  sleep 2
done

# Run migrations
php artisan migrate --force || true

# Cache (only if APP_KEY exists)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php-fpm -D
nginx -g "daemon off;"
