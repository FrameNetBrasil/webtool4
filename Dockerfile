FROM framenetbrasil/php-fpm:8.3

RUN chown -R sail:www /www

USER sail
WORKDIR /www

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN if [[ -n "$PROD" ]] ; then composer install; fi
