#!/bin/bash
set -e

echo "🚂 Railway Laravel App Starting with Apache..."

# Ensure we're in the right directory
cd /var/www/html

# Create .env if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📄 Creating .env from .env.example..."
    cp .env.example .env
    echo "✅ .env created"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
fi

# Run migrations if database is available
if [ ! -z "$DATABASE_URL" ]; then
    echo "🗄️ Running migrations..."
    php artisan migrate --force || echo "⚠️ Migrations failed, continuing..."
fi

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache || echo "Config cache skipped"

# Create storage link
php artisan storage:link 2>/dev/null || echo "Storage link already exists"

# Set correct permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache

# Start Apache
echo "🚀 Starting Apache server..."
exec apache2-foreground
