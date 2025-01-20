FROM composer:2.2 AS composer
FROM node:22 AS node

WORKDIR /app
COPY . /app

RUN yarn install
RUN yarn build

FROM dunglas/frankenphp:php8.4.3-alpine


RUN install-php-extensions \
    pcntl \
    pdo_mysql
# Add other PHP extensions here...

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=node /app/public/build /app/public/build

COPY . /app

RUN /usr/bin/composer install

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]