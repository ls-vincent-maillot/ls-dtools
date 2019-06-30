FROM php:7.1.8-apache

COPY . /web/app
COPY ./config/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN chown -R www-data:www-data /web/app \
    && a2enmod rewrite
