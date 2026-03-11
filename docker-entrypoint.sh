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

USER_COUNT=$(php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$kernel = \$app->make(Illuminate\\Contracts\\Console\\Kernel::class); \$kernel->bootstrap(); echo Illuminate\\Support\\Facades\\DB::table('users')->count();" 2>/dev/null || echo "0")

if [ "$USER_COUNT" = "0" ]; then
  echo "Users table is empty, running seeders..."
  php artisan db:seed --force
else
  echo "Users already exist, skipping seeders."
fi

echo "Application initialized successfully!"
exec "$@"
