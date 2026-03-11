# Docker Setup for Repair Requests

This project includes Docker Compose configuration for easy local development.

## Prerequisites
- Docker and Docker Compose installed on your system
- Port 80 (nginx) and 3306 (MySQL) must be available

## Quick Start

### 1. Clone the repository
```bash
git clone <repository-url>
cd repair-requests
```

### 2. Copy environment file
```bash
cp .env.docker .env
```

### 3. Start Docker containers
```bash
docker compose up -d
```

The application will be available at **`http://localhost`**

### 4. Install dependencies (if not already in image)
```bash
docker compose exec app composer install --ignore-platform-req=ext-fileinfo
```

### 5. Generate APP_KEY
```bash
docker compose exec app php artisan key:generate
```

### 6. Run migrations and seeders
```bash
docker compose exec app php artisan migrate --seed
```

## Access the Application

- **Web Application**: http://localhost
- **MySQL Database**: localhost:3306
  - Database: `repair_requests`
  - User: `laravel`
  - Password: `secret`
  - Root password: `root_secret`

## Test User Credentials

| Role | Email | Password |
|------|-------|----------|
| Dispatcher | dispatcher@example.com | password |
| Master 1 | master1@example.com | password |
| Master 2 | master2@example.com | password |

## Common Commands

### View logs
```bash
docker compose logs -f app
```

### Access MySQL
```bash
docker compose exec db mysql -u laravel -p repair_requests
```

### Run tests
```bash
docker compose exec app php vendor/bin/phpunit tests/Feature
```

### Run seeders only
```bash
docker compose exec app php artisan db:seed
```

### Clear cache
```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

### Stop containers
```bash
docker compose down
```

### Stop and remove volumes (clean state)
```bash
docker compose down -v
```

## Troubleshooting

### MySQL connection error
Wait for MySQL to start. Check logs:
```bash
docker compose logs db
```

### Permission denied errors
Run with proper permissions:
```bash
docker compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
```

### Port already in use
Change `APP_PORT` or `DB_PORT` in `.env` before starting containers.

## Production Deployment

For production deployment, adjust:
1. `.env` — set `APP_DEBUG=false`, `APP_ENV=production`
2. `docker-compose.yml` — use specific image versions
3. Add SSL/TLS configuration in `nginx.conf`
4. Use a managed database service instead of Docker MySQL
