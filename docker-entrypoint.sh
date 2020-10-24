#!/bin/bash
set -e

if [ "$1" = 'start-apache' ]; then
   if [ ! -f /etc/apache2/ssl/archiveui.crt ] || [ ! -f /etc/apache2/ssl/archiveui.key ]; then
       openssl req -x509 -nodes -newkey rsa:2048 \
         -keyout /etc/apache2/ssl/archiveui.key \
         -out    /etc/apache2/ssl/archiveui.crt \
         -subj   "/C=ES/ST=Teruel/L=Teruel/O=Self Signed/OU=IT Department/CN=archiveui"
   fi
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
    source /etc/apache2/envvars
    exec /usr/sbin/apache2ctl -DFOREGROUND
fi

exec "$@"
