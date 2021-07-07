FROM node:lts-alpine as frontend
WORKDIR /app
COPY package*.json /app/
COPY webpack.mix.js /app/
COPY resources/ /app/resources/
RUN npm install && npm run production

FROM composer as composer

FROM php:7.4-fpm-alpine as app
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./dockerize/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN apk add --update-cache \
    nginx \
    supervisor \
  && rm -rf /var/cache/apk/*
COPY ./dockerize/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./dockerize/nginx/app.conf /etc/nginx/conf.d/default.conf
COPY ./dockerize/supervisor/supervisord.conf /etc/supervisord.conf
WORKDIR /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --chown=www-data:www-data . /app
COPY --from=frontend /app/public/js/ /app/public/js/
COPY --from=frontend /app/public/css/ /app/public/css/
COPY --from=frontend /app/mix-manifest.json /app/mix-manifest.json
RUN composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --optimize-autoloader
RUN mkdir /app/storage/app/public
RUN php artisan storage:link
RUN { echo "*	*	*	*	*	/usr/local/bin/php /app/artisan schedule:run >> /dev/null 2>&1"; } | crontab -u www-data -
COPY ./dockerize/entrypoint.sh /opt/entrypoint.sh
RUN chmod +x /opt/entrypoint.sh
ENTRYPOINT ["/opt/entrypoint.sh"]
EXPOSE 80