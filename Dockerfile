FROM php:7.4-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  zip \
  npm \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

RUN pecl install redis-5.1.1 \
  && pecl install xdebug-2.8.1 \
  && docker-php-ext-enable redis xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install git -y

WORKDIR /var/www

COPY . ./

RUN npm install --global yarn

CMD [ "php", "artisan", "serv", "--host=0.0.0.0" ]