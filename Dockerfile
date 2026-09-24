FROM php:8.2-apache

# Install PDO MySQL and other necessary extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache DocumentRoot to point to ecopulse/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/ecopulse/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf || true
RUN echo '<Directory /var/www/html/ecopulse/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# Copy project files into container
COPY . /var/www/html/

# Set working directory & permissions
WORKDIR /var/www/html
RUN mkdir -p /var/www/html/ecopulse/storage/uploads/reports && chmod -R 777 /var/www/html/ecopulse/storage

# Render provides the PORT environment variable (default 80 or 10000)
ENV PORT 10000
EXPOSE 10000

# Script to configure port dynamically for Render
RUN echo '#!/bin/bash\nsed -i "s/Listen 80/Listen ${PORT:-10000}/g" /etc/apache2/ports.conf\nsed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT:-10000}>/g" /etc/apache2/sites-available/000-default.conf\nexec apache2-foreground' > /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]
