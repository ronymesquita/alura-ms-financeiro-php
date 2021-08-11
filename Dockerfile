FROM php

RUN pecl install swoole
RUN docker-php-ext-enable swoole