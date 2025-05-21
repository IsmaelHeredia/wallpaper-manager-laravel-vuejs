FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libonig-dev \
    libxml2-dev \
    curl \
    git \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd dom

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY docker/docker-entrypoint.sh /var/www/html/docker/docker-entrypoint.sh
RUN chmod +x /var/www/html/docker/docker-entrypoint.sh

ENTRYPOINT ["bash", "/var/www/html/docker/docker-entrypoint.sh"]
