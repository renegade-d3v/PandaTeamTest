# Use the official PHP 8.3 image with Apache
FROM php:8.3-apache

LABEL maintainer="renegade-d3v"

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

# Install packages
RUN apt update \
    && apt upgrade -y \
    && apt install -y \
          curl \
          zip \
          unzip \
          libicu-dev \
          libpng-dev \
          libjpeg-dev \
          libwebp-dev \
          libxpm-dev \
          libfreetype6-dev \
          libxslt1-dev \
          librecode0 \
          librecode-dev \
          libonig-dev \
          libxml2-dev  \
          libzip-dev \
          zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Install extension from pecl
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install PHP Extensions
RUN set -eux; \
    docker-php-ext-install bcmath intl pdo_mysql mbstring pcntl soap sockets xml xmlwriter zip; \
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd

# Copy files to the container
COPY . /var/www/html

# Change the owner and group of files and folders to ensure correct access rights
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setup workdir
WORKDIR /var/www/html

# Setup permissions for cache and storage folders
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy vhost, supervisor, cronjobs settings
COPY ./docker/virtual-host.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY ./docker/cronjobs /etc/cron.d/cronjobs
RUN chmod 0644 /etc/cron.d/cronjobs
RUN crontab /etc/cron.d/cronjobs

# Enable the Apache module
RUN a2enmod rewrite headers

# Add localhost to the Apache configuration file
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Run Laravel Artisan commands
RUN php artisan key:generate && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate

# Generate the Composer class autoloader
RUN composer dump-autoload
