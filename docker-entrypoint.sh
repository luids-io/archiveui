#!/bin/bash
set -e

if [ "$1" = 'start-apache' ]; then
   if [ ! -f /var/www/.docker-firstrun ]; then
       pushd /var/www >/dev/null
       if [ "x${INSTALL_APP_KEY}x" == "xx" ]; then
           php artisan key:generate
       else
           sed -i "s/APP_KEY=/APP_KEY=${INSTALL_APP_KEY}/g" /var/www/.env
       fi
       php artisan db:seed --force
       popd >/dev/null
       touch /var/www/.docker-firstrun
    fi
    exec /usr/sbin/apache2ctl -DFOREGROUND
fi

exec "$@"
