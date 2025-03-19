FROM php:7.4-apache

COPY . /var/www/html

WORKDIR /var/www/html/src

RUN docker-php-ext-install mysqli

RUN echo $(openssl rand -hex 16) > /root/root.txt

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080"]