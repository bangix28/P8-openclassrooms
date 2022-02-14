FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
curl \
wget \
zip \
git

RUN apt install gnupg -y
RUN apt-get install default-mysql-client -y
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -fsSL https://deb.nodesource.com/setup_17.x | \
apt-get install -y nodejs \
npm

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug


ADD ./p8 /usr/src/myapp

EXPOSE 9000

WORKDIR /usr/src/myapp