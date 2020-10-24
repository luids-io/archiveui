FROM ubuntu:20.04

LABEL maintainer="Luis Guillén Civera <luisguillenc@gmail.com>"

## install software packages
ARG DEBIAN_FRONTEND=noninteractive
RUN apt update && \
    apt install -y libapache2-mod-php7.4 php7.4-gd php7.4-mbstring \
        php7.4-xml php7.4-zip php7.4-sqlite3 php-mongodb composer && \
    rm -rf /var/lib/apt/lists/* && \
    apt clean

## configure apache
COPY apache-site.conf /etc/apache2/sites-available/archiveui.conf
COPY apache-site-ssl.conf /etc/apache2/sites-available/archiveui-ssl.conf

RUN mkdir -p /etc/apache2/ssl && chmod 700 /etc/apache2/ssl && \
    rm /etc/apache2/sites-enabled/* && \
    a2enmod rewrite && a2enmod env && a2enmod ssl && \
    a2ensite archiveui && a2ensite archiveui-ssl

## copy base software (it uses .dockerignore)
COPY . /var/www
WORKDIR /var/www
## get dependencies
RUN /usr/bin/composer install

## tune app
ENV APP_URL http://localhost
ENV INSTALL_APP_KEY ""
ENV ARCHIVE_HOST mongodb
ENV ARCHIVE_PORT 27017
ENV ARCHIVE_DATABASE luidsdb
ENV ARCHIVE_USERNAME ""
ENV ARCHIVE_PASSWORD ""
ENV ADMIN_NAME Administrator
ENV ADMIN_USERNAME admin
ENV ADMIN_EMAIL ""
ENV ADMIN_PASSWORD="admin"

RUN touch /var/www/storage/database/database.sqlite && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    mv .env.prod .env && \
    php artisan config:clear && php artisan cache:clear && \
    php artisan route:clear && php artisan view:clear && \
    php artisan migrate:install && php artisan migrate --force

## setup entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod 755 /usr/local/bin/docker-entrypoint.sh && \
    ln -s /usr/local/bin/docker-entrypoint.sh /

EXPOSE 80 443

VOLUME [ "/var/www/storage", "/etc/apache2/ssl" ]

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["start-apache"]
