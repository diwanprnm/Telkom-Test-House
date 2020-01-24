FROM telkomindonesia/alpine:php-7.1-apache-novol

WORKDIR /var/www/data/html

USER root

COPY . .

RUN mkdir -p \
      bootstrap/cache \
      storage/app \
      storage/logs \
      storage/tmp \
      storage/framework/cache \
      storage/framework/sessions \
      storage/framework/views \
    && chmod 775 -R \
        bootstrap/cache \
        storage/app \
        storage/logs \
        storage/tmp \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
    && touch \
        storage/logs/laravel.log \
    && chmod 664 \
        storage/logs/laravel.log \
    && sed -i -e 's|/var/www/data/html|/var/www/data/html/public|g' \
        /usr/local/docker/etc/apache2/httpd.conf \
    && composer install 

USER user