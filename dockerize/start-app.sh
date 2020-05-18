#!/bin/bash

# Start php-fpm
/usr/local/sbin/php-fpm

# Start Nginx
/usr/sbin/nginx -g 'daemon off;'