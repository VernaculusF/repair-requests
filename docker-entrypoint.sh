#!/bin/sh
set -e

echo "Waiting for MySQL to be ready..."
while ! nc -z db 3306; do
  echo "MySQL is unavailable - sleeping"
  sleep 1
done
echo "MySQL is up!"

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Cache clearing..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "Application initialized successfully!"
exec "$@"
