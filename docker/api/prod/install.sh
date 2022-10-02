#!/bin/sh

composer install

symfony console --no-interaction doctrine:migrations:migrate

php-fpm