FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create SQLite database file and set permissions
RUN mkdir -p /app/database && touch /app/database/database.sqlite

EXPOSE 10000

# Run migrations and start Laravel built-in server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000