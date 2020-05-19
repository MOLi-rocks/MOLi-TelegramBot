#!/bin/bash

# Start cron
crond

# Start supervisor
supervisord -c /etc/supervisord.conf

# Start Nginx
/usr/sbin/nginx

# Start php-fpm
/usr/local/sbin/php-fpm