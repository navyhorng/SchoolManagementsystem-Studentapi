#!/usr/bin/env bash
set -e

# Laravel setup
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# If you use migrations in production:
php artisan migrate --force || true

php-fpm -D
nginx -g "daemon off;"
