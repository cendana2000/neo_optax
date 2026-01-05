FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    cron \
    supervisor \
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

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/app

EXPOSE 9000

CMD [ "/usr/bin/supervisord" ]