FROM telkomindonesia/alpine:php-7.1-nginx-novol

WORKDIR /var/www/data/html

ARG ARGS_APP_ENV=local

COPY composer.* ./
RUN composer install --no-scripts --no-autoloader

COPY . .

USER root
RUN chmod -R 775 /var/www/data/html \
    && chmod 755 /var/www/data/html/start \
    && sed -i -e "s|local|${ARGS_APP_ENV}|g" ./.env.example \
    && sed -i -e "s|${ARGS_APP_ENV}host|localhost|g" ./.env.example

RUN apk update \
    && apk add mysql-client \
    && apk add ghostscript

USER user

CMD ["./start"]