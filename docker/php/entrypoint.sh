#!/bin/bash
set -e

# Wait for MySQL to be ready
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "Waiting for database..."
    while ! nc -z $DB_HOST $DB_PORT; do
      sleep 1
    done
    echo "Database is up!"
fi

# Install dependencies if autoload is missing
if [ ! -f "vendor/autoload.php" ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# Set permissions and prepare internal cache
echo "Setting permissions and preparing internal cache..."
mkdir -p /tmp/laravel_views
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Force rights (Crucial for Linux Production)
# We use || true to ignore errors if the user doesn't have enough permissions
# but usually, chmod should work if the user owns the files.
chmod -R 777 /tmp/laravel_views storage bootstrap/cache || true

# Only attempt chown if running as root
if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data /tmp/laravel_views storage bootstrap/cache || true
fi

# Run migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Optimization for production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration and routes..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"
