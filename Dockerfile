FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    libssl-dev \
    libicu-dev \
    libxml2-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql intl xml

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock /var/www/html/

COPY . /var/www/html/

EXPOSE 9000

CMD ["bash"]
