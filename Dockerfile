FROM php:8.3-fpm-alpine

# Extensions système
RUN apk add --no-cache \
    git \
    curl \
    libpq-dev \
    icu-dev \
    zip \
    unzip \
    netcat-openbsd

# Extensions PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    opcache

WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]