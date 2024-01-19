FROM php:8.2-apache

# Install PDO MySQL driver
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Allow .htaccess with RewriteEngine
RUN a2enmod rewrite \
    && sed -i -e 's,/var/www/html,/var/www/html/public,g' /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80
