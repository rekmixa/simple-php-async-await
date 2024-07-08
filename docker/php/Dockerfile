FROM php:8.3.9-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USER_ID=1000

COPY bootstrap.sh /usr/local/bin/bootstrap.sh
RUN chmod +x /usr/local/bin/bootstrap.sh
RUN /usr/local/bin/bootstrap.sh

WORKDIR /app
ADD . /app

RUN chown -R www-data:www-data /app

RUN export PHP_AUTOCONF=/usr/bin/autoconf

RUN apk update && apk upgrade
RUN apk add --no-cache \
        postgresql-dev \
        php8-pear \
        autoconf \
        automake \
        libtool \
        build-base \
        libsodium \
        libsodium-dev

RUN pecl install \
        opcache \
        libsodium-stable

RUN docker-php-ext-enable \
        opcache \
        sodium

RUN docker-php-ext-install pdo pdo_pgsql
