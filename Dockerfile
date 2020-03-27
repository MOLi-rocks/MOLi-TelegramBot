FROM node:lts-alpine as frontend
WORKDIR /src/app
COPY package*.json /src/app/
COPY webpack.mix.js /src/app/
COPY resources/ /src/app/resources/
RUN npm install && npm run production

FROM composer as composer

FROM php:7.2-fpm-alpine as app
WORKDIR /src/app
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY . /src/app
COPY --from=frontend /src/app/public/js/ /src/app/public/js/
COPY --from=frontend /src/app/public/css/ /src/app/public/css/
COPY --from=frontend /src/app/mix-manifest.json /src/app/mix-manifest.json
RUN composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --optimize-autoloader