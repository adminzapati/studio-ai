#!/bin/bash
set -e

# --- CONFIGURATION ---
PROJECT_DIR="/www/wwwroot/studioai" # <--- CHECK THIS PATH
USER="www"
GROUP="www"
# ---------------------

echo "Starting deployment..."

# Navigate to project directory
cd "$PROJECT_DIR" || { echo "Error: Project directory $PROJECT_DIR not found!"; exit 1; }

# Pull latest code
echo "Pulling latest changes from git..."
git reset --hard
git pull origin main

# Install PHP dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies & Build Assets (CRITICAL for Tailwind)
echo "Building Front-end Assets..."
npm install
npm run build

# Run Migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear & Cache Config
echo "Optimizing application..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions (Optional but recommended)
echo "Fixing permissions..."
chown -R $USER:$GROUP storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Restart Queue (if using Supervisor)
echo "Restarting queue workers..."
php artisan queue:restart

echo "Deployment finished successfully!"
