#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader
npm ci --include=dev
npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate application key if it doesn't exist
if [ ! -f ".env" ]; then
    echo "APP_KEY=" > .env
fi

php artisan key:generate --force

# Run migrations
php artisan migrate --force
