# Build stage
FROM php:8.2-fpm-alpine AS builder

# Install build dependencies
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    mariadb-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pcntl \
    zip \
    mbstring \
    pdo \
    pdo_mysql

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader --ignore-platform-req=ext-fileinfo

# ---

# Runtime stage
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    libzip \
    oniguruma \
    mariadb-connector-c \
    netcat-openbsd

# Copy compiled PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Copy application from builder
COPY --from=builder /app /app

# Set working directory
WORKDIR /app

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 0755 /app/storage /app/bootstrap/cache \
    && chmod +x /app/docker-entrypoint.sh

# Expose port
EXPOSE 9000

ENTRYPOINT ["/app/docker-entrypoint.sh"]
# Start PHP-FPM
CMD ["php-fpm"]
