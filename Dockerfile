FROM telkomindonesia/alpine:php-7.1-nginx-novol

WORKDIR /var/www/data/html

COPY composer.* ./

RUN composer install --no-scripts --no-autoloader

COPY . .

USER root

RUN chmod -R 775 /var/www/data/html \
    && chmod 755 /var/www/data/html/start

USER user

CMD ["./start"]
