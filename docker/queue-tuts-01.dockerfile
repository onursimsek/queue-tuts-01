FROM ubuntu:latest

RUN apt-get upgrade -y
RUN apt-get update
RUN apt-get install -y apache2 php php-bcmath
RUN /etc/init.d/apache2 restart