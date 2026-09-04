# Use an official PHP image with Apache
FROM php:8.2-apache

# Enable extensions required by the application and Composer dependencies.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl libcurl4-openssl-dev libfreetype6-dev libicu-dev libjpeg62-turbo-dev \
        libonig-dev libpng-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" curl gd intl mbstring mysqli pdo pdo_mysql xml zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Production-safe PHP defaults. Runtime errors are logged, not rendered into responses.
RUN { \
        echo "date.timezone=Asia/Manila"; \
        echo "display_errors=Off"; \
        echo "display_startup_errors=Off"; \
        echo "log_errors=On"; \
        echo "expose_php=Off"; \
        echo "upload_max_filesize=35M"; \
        echo "post_max_size=36M"; \
        echo "session.cookie_httponly=1"; \
        echo "session.cookie_samesite=Lax"; \
    } > /usr/local/etc/php/conf.d/hshr.ini

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite headers
RUN { echo "ServerTokens Prod"; echo "ServerSignature Off"; } > /etc/apache2/conf-available/hshr-security.conf \
    && a2enconf hshr-security

# Permit the application's .htaccess rewrite rules.
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Install locked PHP dependencies, then copy application files.
COPY composer.json composer.lock /var/www/html/
RUN composer install --working-dir=/var/www/html --no-dev --no-interaction --prefer-dist --classmap-authoritative

COPY . /var/www/html/

# Set the correct working directory
WORKDIR /var/www/html/

# Keep source read-only to the web user while allowing only upload directories
# to receive application-generated files.
RUN mkdir -p /var/www/html/uploads/employee_documents /var/www/html/uploads/profile_pictures \
        /var/www/html/applicants_uploads /var/www/html/staff_side/uploads \
    && chown -R root:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} + \
    && find /var/www/html -type f -exec chmod 644 {} + \
    && chown -R www-data:www-data /var/www/html/uploads /var/www/html/applicants_uploads /var/www/html/staff_side/uploads \
    && find /var/www/html/uploads /var/www/html/applicants_uploads /var/www/html/staff_side/uploads -type d -exec chmod 775 {} +

# Expose port 80 for web traffic
EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl --fail --silent --show-error http://127.0.0.1/health.php || exit 1

# Start Apache server
CMD ["apache2-foreground"]
