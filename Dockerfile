FROM php:5-apache
MAINTAINER Leny <info@flatland.be>

RUN a2enmod rewrite

COPY . /var/www/html
WORKDIR /var/www/html
RUN chown -R www-data: /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
