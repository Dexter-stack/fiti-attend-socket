# Use the official PHP image as base
FROM php:8.0.2

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


RUN php artisan config:clear

# Set permissions
RUN chown -R www-data:www-data /app/storage
RUN chmod -R 775 /app/storage

# Expose port 8000 for the built-in PHP server
EXPOSE 8000


# Use the built-in PHP server for development
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]