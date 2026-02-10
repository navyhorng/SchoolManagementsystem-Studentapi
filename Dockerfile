FROM php:8.4-fpm

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    nginx git unzip curl \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
    libicu-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring zip exif pcntl intl gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first (better caching + more stable builds)
COPY composer.json composer.lock ./

# Install PHP deps in Linux container environment
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy the rest of the app
COPY . .

# Nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 storage bootstrap/cache

# Start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
