FROM php:8.3-apache

WORKDIR /var/www/html

COPY index.php submit.php ./

COPY style.css ./assets/css/style.css

COPY script.js jquery-3.6.1.min.js ./assets/js/

COPY favicon.svg ./assets/favicon.svg

RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:10000>/' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000
