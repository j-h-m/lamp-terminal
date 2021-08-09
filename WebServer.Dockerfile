FROM ubuntu:20.04
# noninteractive environment (no install requests for setup)
ENV DEBIAN_FRONTEND=noninteractive
# copy app files
# COPY ./www /usr/src/app
COPY ./www /var/www/html
# install requirements
RUN apt-get update; apt-get -y upgrade
RUN apt-get -y install fish htop
RUN apt-get -y install apache2 mysql-server php \
    libapache2-mod-php php-mysql php-dev php-curl \
    php-json php-common php-mbstring
# entrypoint
CMD ["apachectl","-D","FOREGROUND"]
# ports
EXPOSE 80
EXPOSE 443