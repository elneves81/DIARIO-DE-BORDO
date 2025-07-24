#!/bin/bash
set -e

echo "🚂 Railway Laravel App Starting..."

# Ensure we're in the right directory
cd /app 2>/dev/null || cd /workspace 2>/dev/null || cd /var/www 2>/dev/null || echo "Using current directory"

# Create .env if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📄 Creating .env from .env.example..."
    cp .env.example .env
    echo "✅ .env created"
fi

# Install PHP dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Install Node dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node dependencies..."
    npm ci
fi

# Build assets if not exist
if [ ! -d "public/build" ]; then
    echo "🎨 Building assets..."
    npm run build
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

# Start the server
echo "🚀 Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
