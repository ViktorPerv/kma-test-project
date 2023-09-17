FROM php:8.2-fpm

# Installing dependencies for the PHP modules
RUN apt-get update && \
    apt-get install -y zip curl libcurl3-dev libzip-dev libpng-dev libonig-dev libxml2-dev locales unzip git \
    && pecl install xdebug \
    && docker-php-ext-install sockets \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /tmp/pear
    # libonig-dev is needed for oniguruma which is needed for mbstring

# Installing additional PHP modules
RUN docker-php-ext-install curl gd mbstring mysqli pdo pdo_mysql xml

ENV LANG en_US.UTF-8

# Install Composer so it's available
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

###########################################################################
# xDebug:
###########################################################################

ARG PHP_ENABLE_XDEBUG=0

RUN if [ ${PHP_ENABLE_XDEBUG} = 1 ]; then \
  docker-php-ext-enable xdebug \
;fi
