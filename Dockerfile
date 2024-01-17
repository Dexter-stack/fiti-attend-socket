# Use the official PHP image as base
FROM php:7.4-fpm

# Set working directory
WORKDIR /app

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip \
        libonig-dev \
        libxml2-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Laravel application files
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --no-scripts

# Set up Laravel
RUN cp .env.example .env
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /app/storage
RUN chmod -R 775 /app/storage

# Expose port 9000 for FastCGI
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]