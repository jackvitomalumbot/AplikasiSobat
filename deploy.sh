#!/bin/bash
# ─── DigitalOcean App Platform Run Script ───
# Set this as Run Command in App Spec: bash deploy.sh

set -e

echo "🔧 Running pre-start commands..."

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations (--force required in production)
echo "📦 Running database migrations..."
php artisan migrate --force

# Cache config & routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Link storage
php artisan storage:link --force 2>/dev/null || true

echo "✅ Setup completed! Starting server..."

# Start Apache (DigitalOcean App Platform uses heroku-php-apache2)
heroku-php-apache2 public/
