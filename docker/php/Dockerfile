FROM php:8.3.9-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USER_ID=1000

WORKDIR /app
ADD . /app

COPY bootstrap.sh /usr/local/bin/bootstrap.sh
RUN chmod +x /usr/local/bin/bootstrap.sh
RUN /usr/local/bin/bootstrap.sh

RUN chown -R www-data:www-data /app

RUN export PHP_AUTOCONF=/usr/bin/autoconf

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

ENTRYPOINT ["bootstrap.sh"]
CMD ["php-fpm"]
