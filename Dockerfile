FROM node:lts-alpine as frontend
WORKDIR /app
COPY package*.json /app/
COPY webpack.mix.js /app/
COPY resources/ /app/resources/
RUN npm install && npm run production

FROM composer as composer

FROM php:7.2-fpm-alpine as app
WORKDIR /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY . /app
COPY --from=frontend /app/public/js/ /app/public/js/
COPY --from=frontend /app/public/css/ /app/public/css/
COPY --from=frontend /app/mix-manifest.json /app/mix-manifest.json
RUN composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --optimize-autoloader