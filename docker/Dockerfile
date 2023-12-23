# Use the official PHP 8.3 image with Apache
FROM php:8.3-apache

LABEL maintainer="renegade-d3v"

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

# Install packages
RUN apt update \
    && apt upgrade -y \
    && apt install -y \
          cron \
          curl \
          zip \
          unzip \
          g++ \
          git \
          sudo \
          libbz2-dev \
          libicu-dev \
          libpng-dev \
          libjpeg-dev \
          libwebp-dev \
          libxpm-dev \
          libmcrypt-dev \
          libfreetype6-dev \
          libxslt1-dev \
          librecode0 \
          librecode-dev \
          libonig-dev \
          libxml2-dev  \
          libreadline-dev \
          libzip-dev \
          zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN set -eux; \
    docker-php-ext-install bcmath bz2 intl calendar iconv opcache pdo_mysql mbstring pcntl soap sockets xml xmlwriter zip; \
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd

# Apache configs + document root.
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy cronjobs settings
COPY ./docker/cronjobs /etc/cron.d/cronjobs
RUN chmod 0644 /etc/cron.d/cronjobs
RUN crontab /etc/cron.d/cronjobs

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable apache2 modules
RUN a2enmod rewrite headers

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# We need a user with the same UID/GID as the host user
# so when we execute CLI commands, all the host file's permissions and ownership remain intact.
# Otherwise commands from inside the container would create root-owned files and directories.
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && chown -R devuser:devuser /home/devuser
