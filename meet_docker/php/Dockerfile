FROM php:8.1-fpm-buster

#Some overwrites of the php-fpm pool (which called zz-docker.conf
COPY zzz-docker.conf /usr/local/etc/php-fpm.d/
COPY xdebug.ini /usr/local/etc/php/conf.d/

#Add debian repositores
RUN echo "deb http://ftp.de.debian.org/debian buster main" >> /etc/apt/sources.list

RUN apt-get update
RUN apt-get -y upgrade

#Install some basic lpackages
RUN apt-get -y install nano wget;
RUN apt-get -y install libzip-dev libpng-dev;

RUN apt-get -y install xvfb
RUN apt-get -y install wkhtmltopdf

#Install and enable xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

#Install and enable mysql and pdo libraries
RUN docker-php-ext-install pdo pdo_mysql zip gd

RUN wget https://getcomposer.org/installer -O - -q | php -- --quiet && mv composer.phar /usr/local/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs
RUN npm install npm@latest -g
