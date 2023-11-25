# Use the official PHP image with Apache for Laravel
FROM php:8.1-apache

# Enable the Apache rewrite module
RUN a2enmod rewrite

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Configure the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy application files to the image
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Composer dependencies
RUN composer install

# Change file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap

# Expose port 80
EXPOSE 80

# Command to start Apache
CMD ["apache2-foreground"]
