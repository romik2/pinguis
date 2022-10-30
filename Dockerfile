FROM ubuntu:20.04

RUN apt-get update

#nginx
RUN apt-get install nginx -y

COPY . /var/www/pinguis

#php7.4
RUN apt-get install curl software-properties-common -y
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install php7.4 -y
RUN apt-get install php7.4-cli php7.4-xml php7.4-mysql php7.4-curl -y
RUN apt-get install php7.4-bcmath php7.4-xml php7.4-mbstring php7.4-zip php7.4-gd php7.4-intl php7.4-imagick php7.4-imap php7.4-snmp -y

#composer + directory vendor
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN cd /var/www/pinguis && composer install

EXPOSE 80:8080