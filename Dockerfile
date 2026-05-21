FROM php:8.2-apache

# Install PHP extensions required by CodeIgniter 4
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install pdo pdo_mysql bcmath intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (essential for CI4 routing)
RUN a2enmod rewrite

# Set document root to the public/ folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides (so CI4's rewrite rules work)
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# On startup: create writable dirs in /tmp (avoids Windows NTFS permission issues), then launch Apache
CMD ["sh", "-c", "mkdir -p /tmp/writable/cache /tmp/writable/session /tmp/writable/logs /tmp/writable/debugbar /tmp/writable/uploads && chown -R www-data:www-data /tmp/writable && apache2-foreground"]