FROM php:7.2-apache

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./docker/web/000-default.conf /etc/apache2/sites-available/

RUN apt update -y && apt upgrade -y
RUN apt install -y git curl zip libzip-dev ca-certificates curl gnupg

RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_16.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt update -y && apt install -y nodejs

RUN a2enmod rewrite

EXPOSE 80 443