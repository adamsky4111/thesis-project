# ./docker/php/Dockerfile

FROM php:8.0-fpm

RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
    apt-get install -y \
    libzip-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

WORKDIR /usr/src/apps/api

COPY --chown=1000:1000 apps/api /usr/src/apps/api

RUN PATH=$PATH:/usr/src/apps/api/vendor/bin:bin

RUN composer install --no-scripts --prefer-dist \
    && rm -rf "$(composer config cache-dir)" "$(composer config data-dir)"
