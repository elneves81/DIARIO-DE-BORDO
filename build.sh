#!/usr/bin/env bash
set -o errexit

echo "==> Installing PHP dependencies..."
if [ -f "composer.json" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction || echo "Composer failed, but continuing..."
fi

echo "==> Building assets..."
npm install
npm run build

echo "==> Laravel optimizations..."
if command -v php >/dev/null 2>&1; then
    php artisan config:cache || echo "Config cache skipped"
    php artisan route:cache || echo "Route cache skipped"
    php artisan view:cache || echo "View cache skipped"
    php artisan key:generate --force || echo "Key generation skipped"
    php artisan migrate --force || echo "Migration skipped"
fi

echo "==> Build complete!"
