FROM ubuntu

RUN apt-get update

RUN ln -fs /usr/share/zoneinfo/America/New_York /etc/localtime
RUN apt-get install -y tzdata
RUN dpkg-reconfigure --frontend noninteractive tzdata
RUN apt-get -y install php php-curl curl wget php-mbstring php-gd php-zip zip unzip php-dom
WORKDIR /tmp
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
WORKDIR /var/www/html/
CMD composer install --working-dir=/var/www/html
CMD php /var/www/html/artisan serve --host=0.0.0.0 --port=8080








