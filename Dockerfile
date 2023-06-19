ARG COMPOSER_VERSION=2.3
ARG PHP_VERSION=8.0

FROM composer:${COMPOSER_VERSION} AS composer
FROM mlocati/php-extension-installer AS php_extension_installer

FROM php:${PHP_VERSION}-cli-alpine AS php

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/bin/install-php-extensions

RUN install-php-extensions pdo_sqlite

COPY . /app

WORKDIR /app

RUN composer update --with-all-dependencies --no-interaction --no-progress

WORKDIR /app/tests/Application

RUN php bin/console doctrine:database:create && php bin/console doctrine:schema:update --force

WORKDIR /app
