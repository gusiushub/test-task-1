FROM php:7.4-fpm

WORKDIR /app

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions zip gd intl memcache && docker-php-ext-install mysqli pdo pdo_mysql

ADD conf.d/ $PHP_INI_DIR/conf.d/

RUN apt-get clean && apt-get update && apt-get install -y cron

