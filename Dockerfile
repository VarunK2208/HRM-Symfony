FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

# 🟢 Install MongoDB extension
RUN if ! php -m | grep -q mongodb; then \
        pecl install mongodb && docker-php-ext-enable mongodb; \
    else \
        docker-php-ext-enable mongodb; \
    fi

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
