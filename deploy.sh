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

# Build frontend assets (Three.js + CSS via Vite)
echo "🎨 Building frontend assets..."
npm ci --prefer-offline 2>/dev/null || npm install
npm run build

# Run migrations (--force required in production)
echo "📦 Running database migrations..."
php artisan migrate --force

# Cache config & routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Link storage
php artisan storage:link --force 2>/dev/null || true

# Ensure upload directories exist (ephemeral filesystem)
echo "📁 Creating upload directories..."
mkdir -p public/uploads/profiles
mkdir -p public/uploads/thumbnails
mkdir -p public/uploads/materi
mkdir -p public/uploads/submissions
mkdir -p public/uploads/pengajar-unggulan
mkdir -p public/uploads/prestasi

# Watermarked PDF cache directory
mkdir -p storage/app/watermarked
chmod -R 755 storage/app/watermarked

# Install LibreOffice for Word/PPT → PDF conversion (silent, non-blocking)
echo "📄 Checking LibreOffice..."
if ! command -v soffice &> /dev/null; then
    echo "🔽 Installing LibreOffice (headless)..."
    apt-get update -qq && apt-get install -y --no-install-recommends \
        libreoffice-writer libreoffice-impress libreoffice-calc \
        libreoffice-common fonts-liberation 2>/dev/null || true
fi

echo "✅ Setup completed! Starting server..."

# Start Apache (DigitalOcean App Platform uses heroku-php-apache2)
heroku-php-apache2 public/
