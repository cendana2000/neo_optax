FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpq-dev \
    libpng-dev \
    libicu-dev

RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_pgsql \
    pgsql \
    intl \
    gd \
    sockets \
    bcmath

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/app
EXPOSE 9000
CMD ["php-fpm"]
