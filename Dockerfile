FROM php:7.4-apache

COPY . /var/www/html

WORKDIR /var/www/html/src

RUN docker-php-ext-install mysqli

RUN echo $(openssl rand -hex 16) > /root/root.txt

RUN useradd -m -s /bin/bash user && \
chown -R user:user /var/www/html/src/uploads && \
chmod -R 755 /var/www/html/src/uploads

RUN echo $(openssl rand -hex 16) > /home/user/user.txt

USER user

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80"]