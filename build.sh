#!/usr/bin/env bash
# exit on error
set -o errexit

echo "==> Installing PHP if needed..."
if ! command -v php &> /dev/null; then
    echo "PHP not found, installing..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
else
    echo "PHP found!"
fi

echo "==> Installing Composer if needed..."
if ! command -v composer &> /dev/null; then
    echo "Composer not found, installing..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    export PATH="/usr/local/bin:$PATH"
fi

echo "==> Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction || echo "Composer install failed, continuing..."

echo "==> Installing Node dependencies..."
npm ci --include=dev

echo "==> Building assets..."
npm run build

echo "==> Laravel setup..."
if command -v php &> /dev/null; then
    php artisan config:cache || echo "Config cache failed"
    php artisan route:cache || echo "Route cache failed"  
    php artisan view:cache || echo "View cache failed"
    php artisan key:generate --force || echo "Key generate failed"
    php artisan migrate --force || echo "Migration failed"
fi

echo "==> Build complete! Ready for production."
