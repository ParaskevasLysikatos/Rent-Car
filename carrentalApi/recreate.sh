#!/bin/sh

composer dumpautoload || exit 1

php artisan db:wipe --drop-views || exit 1
php artisan migrate:fresh || exit 1
php artisan db:seed || exit 1
