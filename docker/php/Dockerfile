FROM yiisoftware/yii2-php:7.4-fpm

RUN date

RUN apt-get update && apt-get install -y libz-dev libmemcached-dev locales unzip git \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-install sockets \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /tmp/pear

#установка локали
RUN echo 'ru_RU.UTF-8 UTF-8' >> /etc/locale.gen && \
    echo 'en_US.UTF-8 UTF-8' >> /etc/locale.gen  && \
    locale-gen en_US en_US.UTF-8 ru_RU ru_RU.UTF-8 && \
    dpkg-reconfigure locales

ENV LANG en_US.UTF-8

###########################################################################
# xDebug:
###########################################################################

ARG PHP_ENABLE_XDEBUG=0

RUN if [ ${PHP_ENABLE_XDEBUG} = 1 ]; then \
  docker-php-ext-enable xdebug \
;fi
