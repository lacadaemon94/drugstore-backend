FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update 
RUN apt-get install -y libpq-dev git unzip zip
RUN docker-php-ext-install pdo pdo_pgsql zip
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
