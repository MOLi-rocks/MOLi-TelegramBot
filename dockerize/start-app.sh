#!/bin/bash

if [ "$LARAVEL_SCHEDULE_ENABLE" = true ] ; then
  # Start cron
  crond
fi

if [ "$LARAVEL_QUEUE_ENABLE" = true ] ; then
  # Start supervisor
  supervisord -c /etc/supervisord.conf
fi

# Start Nginx
/usr/sbin/nginx

# Start php-fpm
/usr/local/sbin/php-fpm