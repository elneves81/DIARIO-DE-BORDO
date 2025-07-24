#!/usr/bin/env bash
# exit on error
set -o errexit

echo "==> Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Installing Node dependencies..."
npm ci --include=dev

echo "==> Building assets..."
npm run build

echo "==> Caching Laravel configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Generating application key..."
php artisan key:generate --force

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Build complete!"
