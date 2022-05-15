FROM php:8-fpm

RUN apt-get update && apt-get install -y git \
    zip unzip zlib1g-dev libpng-dev libzip-dev lsof \
    && pecl install redis xdebug\
    && docker-php-ext-install pdo_mysql pcntl zip \
    && docker-php-ext-enable redis\
    && docker-php-ext-enable xdebug

ENV PATH="$PATH:/var/www/vendor/bin"
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV DATA_ROOT /var/www

WORKDIR ${DATA_ROOT}

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN chown www:www ${DATA_ROOT}/

COPY composer.json composer.lock ${DATA_ROOT}/
COPY ./. ${DATA_ROOT}/
RUN composer install --no-scripts --no-interaction
RUN composer dump-autoload

RUN chmod -R 775 /var/www/bootstrap
RUN chmod -R 775 /var/www/storage

USER www
