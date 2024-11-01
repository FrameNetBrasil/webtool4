FROM php:8.3-fpm-alpine3.19

ARG PROD

RUN apk add -U --no-cache \
    linux-headers \
    autoconf \
    imap-dev \
    libpng-dev \
    zlib-dev \
    libjpeg-turbo-dev \
    krb5-dev \
    openssl-dev \
    libzip-dev \
    oniguruma-dev \
    make \
    g++ \
    git \
    bash \
    fish

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
    -j$(nproc) gd \
    zip \
    opcache \
    imap \
    fileinfo \
    mbstring \
    pdo_mysql \
    pcntl \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i 's#variables_order = "GPCS"#variables_order = "EGPCS"#g' $PHP_INI_DIR/php.ini \
    && echo "memory_limit = 128M" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "post_max_size = 1000M" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "upload_max_filesize = 1000M" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "max_execution_time = 4000" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "opcache.file_cache=/www/opcache" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "pm.max_children=32" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && pecl install ds \
    && docker-php-ext-enable ds \
    && pecl install redis \
    && pecl install xdebug \
    && pecl install grpc \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable grpc \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && echo "apc.enable_cli=1" >> "$PHP_INI_DIR/php.ini" \
    && echo "apc.enable=1" >> "$PHP_INI_DIR/php.ini" \
    && pecl install openswoole \
    && docker-php-ext-enable openswoole

ARG WWWGROUP=1001
ARG WWWUSER=1000
RUN addgroup -g $WWWGROUP www \
    && adduser -s /usr/bin/fish -D -G www -u $WWWUSER sail \
    && mkdir /var/log/laravel \
    && touch /var/log/laravel/laravel.log \
    && chown -R sail:www /var/log/laravel

COPY . /www
RUN chown -R sail:www /www

USER sail
WORKDIR /www

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN if [[ -n "$PROD" ]] ; then composer install; fi
