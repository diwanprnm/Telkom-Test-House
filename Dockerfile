# Dockerfile
FROM php:7.0-apache
# FROM brunogasparin/laravel-apache:5-onbuild

RUN apt-get update && apt-get install -y \
        libmcrypt-dev \
        git \
        zlib1g-dev \
        && apt-get clean \
        && rm -rf /var/lib/apt/lists/*

# Basic lumen packages
RUN docker-php-ext-install \
        mcrypt \
        mbstring \
        tokenizer \
        zip \
        exif

# Run install mysql PDO and enable mode rewrite
RUN docker-php-ext-install pdo_mysql
# RUN a2enmod rewrite



# install composer
# RUN curl -sS https://getcomposer.org/installer | php && \
#    mv composer.phar /usr/local/bin/composer

# ADD directory website
# ADD . /var/www/html/telkom-dds


WORKDIR /var/www/html

# Download and Install Composer
RUN curl -s http://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Add vendor binaries to PATH
ENV PATH=/var/www/html/vendor/bin:$PATH

# Copy apache conf and php.ini conf
COPY ./misc/docker/apache2.conf /etc/apache2/apache2.conf
COPY ./misc/docker/ports.conf /etc/apache2/ports.conf
COPY ./misc/docker/php.ini /usr/local/etc/php/php.ini
COPY ./misc/docker/.env.prod /var/www/html/.env


COPY . /var/www/html
# RUN composer install --prefer-dist --optimize-autoloader --no-scripts --no-dev --profile --ignore-platform-reqs -vvv
RUN composer install



#RUN php artisan clear-compiled
#RUN php artisan optimize
#RUN php artisan config:cache


# COPY config/docker/000-default.conf /etc/apache2/sites-available/000-default.conf

#  Configuring Apache
RUN sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=root/g' /etc/apache2/envvars &&\
    rm /etc/apache2/sites-available/000-default.conf &&\
    rm /etc/apache2/sites-enabled/000-default.conf &&\
    chgrp -R root /var/www/html/storage /var/www/html/bootstrap/cache &&\
    chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache &&\
    chgrp -R root /var/run/apache2* /var/lock/apache2* &&\
    chmod -R ug+rwx /var/run/apache2* /var/lock/apache2* &&\

    a2enmod rewrite 

# Configure data volume
# VOLUME /var/www/html/storage/app
# VOLUME /var/www/html/storage/framework/sessions
# VOLUME /var/www/html/storage/logs

COPY laravel-apache2-foreground /usr/local/bin/

RUN chmod +x /usr/local/bin/laravel-apache2-foreground

# RUN apt-get update && apt-get -y install zip unzip

# Change directory into project root & install vendor
# RUN cd /var/www/html/telkom-dds && \
#	composer install

# Change file permission 775
# RUN cd /var/www/html && \
#	chmod -R 775 telkom-dds && \
#	chown -R www-data:www-data telkom-dds

# open port 443
# EXPOSE 80
# EXPOSE 8000
# EXPOSE 443


EXPOSE 8080 8443
CMD ["laravel-apache2-foreground"]
