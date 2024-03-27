FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        curl \
        wget \
        mc \
        git \
        libonig-dev \
        libzip-dev \
        libpng-dev \
        libcurl4-gnutls-dev \
        libicu-dev \
        libxml2-dev \
        libsqlite3-dev \
        locales \
        locales-all \
        zip \
        libpq-dev \
    && locale-gen ru_RU.UTF-8 \
    && docker-php-ext-install -j$(nproc) iconv mbstring zip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd  \
    && docker-php-ext-install -j$(nproc) intl soap xml opcache bcmath pcntl \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install -j$(nproc) pcntl \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && pecl install runkit7-alpha && docker-php-ext-enable runkit7 \
    && rm -rf /tmp/pear \
    && rm -frv /var/cache/apk/*

# Куда же без composer'а.
RUN  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Настройка PHP
RUN echo "post_max_size = 200M" > /usr/local/etc/php/conf.d/40-custom.ini \
    && echo "upload_max_filesize = 200M" >> /usr/local/etc/php/conf.d/40-custom.ini \
    && echo "date.timezone = Europe/Moscow" >> /usr/local/etc/php/conf.d/40-custom.ini \
    && echo "xdebug.mode = coverage" >> /usr/local/etc/php/conf.d/40-custom.ini \
    && echo "xdebug.log = \"/var/www/docker/xdebug.log\"" >> /usr/local/etc/php/conf.d/40-custom.ini \
    && echo "memory_limit = -1" >> /usr/local/etc/php/conf.d/40-custom.ini

ENV LANG ru_RU.UTF-8
ENV LANGUAGE ru_RU:ru
ENV LC_ALL ru_RU.UTF-8
ENV TZ=Europe/Moscow

# Указываем рабочую директорию для PH
WORKDIR /var/www

CMD ["php-fpm"]