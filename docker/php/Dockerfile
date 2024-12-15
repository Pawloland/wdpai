FROM php:8.4.1-fpm-alpine3.21

## Install Xdebug and other necessary tools
RUN apk add --no-cache $PHPIZE_DEPS linux-headers\
    && pecl install xdebug-3.4.0\
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS

# Copy the application code
COPY . /app

VOLUME ["/app"]
