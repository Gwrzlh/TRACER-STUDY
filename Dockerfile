# ===============================
# Stage 1: Composer dependencies
# ===============================
FROM php:8.1-cli AS deps

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader

# ===============================
# Stage 2: Apache + PHP runtime
# ===============================
FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    intl \
    gd \
    zip \
    mysqli \
    pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set public as DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copy source code
COPY . /var/www/html

# Copy vendor
COPY --from=deps /app/vendor /var/www/html/vendor

# Permission CI4
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/writable

USER www-data
