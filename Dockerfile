FROM ubuntu:20.04

LABEL maintainer="Luis Guillén Civera <luisguillenc@gmail.com>"

## install software packages
ARG DEBIAN_FRONTEND=noninteractive
RUN apt update && \
    apt install -y libapache2-mod-php7.4 php7.4-gd php7.4-mbstring \
        php7.4-xml php7.4-zip php-mongodb composer && \
    rm -rf /var/lib/apt/lists/* && \
    apt clean

## configure apache
COPY apache-site.conf /etc/apache2/sites-available/archiveui.conf
RUN rm /etc/apache2/sites-enabled/* && \
    a2enmod rewrite && \
    a2enmod env && \
    a2ensite archiveui

## copy base software (it uses .dockerignore)
COPY . /var/www
WORKDIR /var/www
## get dependencies
RUN /usr/bin/composer install

## tune app
ENV APP_URL http://localhost
ENV DB_CONNECTION mongodb
ENV DB_HOST mongodb
ENV DB_PORT 27017
ENV DB_DATABASE luidsdb
ENV DB_USERNAME ""
ENV DB_PASSWORD ""

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    mv .env.prod .env

RUN php artisan config:clear && php artisan cache:clear && \
    php artisan route:clear && php artisan view:clear && \
    php artisan key:generate 

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]
