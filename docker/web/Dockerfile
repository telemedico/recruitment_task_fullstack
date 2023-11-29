FROM php:7.2-apache

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./docker/web/000-default.conf /etc/apache2/sites-available/

RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y git curl zip libzip-dev

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

RUN a2enmod rewrite

RUN service apache2 restart

EXPOSE 80 443