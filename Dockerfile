FROM php:7.1

ADD . /usr/src/app
WORKDIR /usr/src/app

RUN apt-get update

RUN apt-get install zlib1g-dev
RUN docker-php-ext-install pdo pdo_mysql zip

RUN apt-get install wget -y
RUN ./install-composer.sh
RUN mv ./composer.phar /usr/bin/composer

# Do not run as root - insecure to run "composer install" as well as files created by the container
# will have the root user as owner in the host system.
RUN groupadd appuser
RUN useradd -r -u 1000 -g appuser appuser
USER appuser
