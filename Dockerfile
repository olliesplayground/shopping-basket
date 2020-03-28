FROM composer:1.10

COPY . /app

WORKDIR /app

RUN composer install

CMD composer phpunit:test && php index.php
