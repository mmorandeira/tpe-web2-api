FROM php:8.1.0-fpm

RUN apt-get update
RUN apt-get install -y git
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer
RUN pecl install xdebug-3.1.5
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install pdo pdo_mysql