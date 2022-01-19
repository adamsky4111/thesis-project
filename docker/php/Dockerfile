# ./docker/php/Dockerfile

FROM php:8.0-fpm

RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    zip \
    cron \
    build-essential \
    curl \
    sudo \
    telnet \
    unzip \
    libzip-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libonig-dev \
    zlib1g-dev \
    vim \
    graphviz \
    supervisor \
    g++

RUN apt-get update && \
    apt-get install -y libmagickwand-dev --no-install-recommends && \
    pecl install imagick && \
    docker-php-ext-enable imagick

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip exif pcntl

RUN chown -R www-data:www-data /usr/src
RUN chmod -R 777 /usr/src

COPY --chown=1000:1000 docker/php/crontab /etc/cron.d/crontab

COPY --chown=1000:1000 apps/api /usr/src/apps/api

RUN chmod 0777 /etc/cron.d/crontab
RUN chmod 0777 -R /var/run/

RUN crontab /etc/cron.d/crontab

RUN touch /var/log/cron.log

WORKDIR /usr/src/apps/api

#COPY --chown=1000:1000 apps/api/var/log/cron.log /usr/src/apps/api/cron.log
#ADD crontab /etc/cron.d/crontab
RUN #chmod 0644 /etc/cron.d/crontab
RUN #crontab /etc/cron.d/crontab


RUN #touch /var/run/crond.pid
RUN #useradd -l -u 1001510000 -c "1001510000" 1001510000

#RUN groupadd crond-users && \
#    chgrp crond-users /var/run/crond.pid && \
#    usermod -a -G crond-users 1001510000
#
#USER 1001510000

RUN #crontab /etc/cron.d/crontab

RUN PATH=$PATH:/usr/src/apps/api/vendor/bin:bin

RUN composer install --no-scripts --prefer-dist \
    && rm -rf "$(composer config cache-dir)" "$(composer config data-dir)"

#EXPOSE: 9000
#COPY ./docker/php/run-server.sh ./run-server.sh
RUN #chmod u+x ./run-server.sh
RUN #./run-server.sh
RUN #./docker/php/run-server.sh
#CMD cron && tail -f /var/log/cron.log
