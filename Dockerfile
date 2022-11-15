FROM php:7.4-fpm-buster
ARG TIMEZONE

COPY conf/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    cron \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

COPY . /app
EXPOSE 9000
RUN echo "* * * * * root php /app/bin/console app:ping-tool >> /app/cron.log 2>&1" >> /etc/crontab
RUN echo "* * * * * root php /app/bin/console app:ping-tool-port >> /app/cron.log 2>&1" >> /etc/crontab
CMD bash -c "cron && php-fpm && tail -f /app/cron.log"