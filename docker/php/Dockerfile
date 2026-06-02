FROM dunglas/frankenphp:php8.4-alpine

# Install PHP extensions using the built-in installer
# This is much simpler than the manual compile process in the PHP-FPM setup
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
# FrankenPHP expects the application in /app
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create required Laravel directories
RUN mkdir -p storage/logs \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# Copy custom configuration files
COPY docker/php/Caddyfile /etc/caddy/Caddyfile
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# FrankenPHP exposes ports 80 (HTTP), 443 (HTTPS), and 443/udp (HTTP/3)
EXPOSE 80 443

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
