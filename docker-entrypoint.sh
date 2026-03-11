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

if [ ! -f /app/storage/.seeded ]; then
  echo "Running seeders..."
  php artisan db:seed --force
  touch /app/storage/.seeded
else
  echo "Seeders already ran, skipping."
fi

echo "Cache clearing..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "Application initialized successfully!"
exec "$@"
