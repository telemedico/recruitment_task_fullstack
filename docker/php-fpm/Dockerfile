FROM php:7.2-fpm-alpine

WORKDIR /var/www/application

RUN apk update

RUN apk add \
    bash \
    git  \
    curl  \
    zip  \
    libzip-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk update
RUN apk add symfony-cli

EXPOSE 9000
CMD ["php-fpm"]
