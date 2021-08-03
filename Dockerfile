FROM php:7.4.21-fpm

# Intalación de módulos php
RUN apt-get update
RUN apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \		
        libedit-dev \
        libxml2-dev \
        libzip-dev \
        libcurl4-gnutls-dev \
        libmagickwand-dev \
	unzip

RUN docker-php-ext-install gd && \
 	docker-php-ext-install json && \
	docker-php-ext-install mbstring && \
	docker-php-ext-install mysqli && \
	docker-php-ext-install opcache && \
	docker-php-ext-install readline && \
	docker-php-ext-install soap && \
	docker-php-ext-install zip && \ 
    pecl install solr && \
	docker-php-ext-enable solr && \
    pecl install imagick && \
	docker-php-ext-enable imagick && \
    pecl install xdebug && \
	docker-php-ext-enable xdebug
	

RUN cd ~ && curl -L https://github.com/kompadre/comp_inject/archive/refs/heads/master.zip > ~/comp_inject.zip && \
	apt update && apt install unzip && \
	unzip ~/comp_inject.zip && \
	cd ~/comp_inject-master && phpize && ./configure && make && make install

# Purga de cache de apt
RUN cd ${WORKDIR} && rm -rf ~/comp_inject.zip ~/comp_inject-master
RUN apt-get remove --purge unzip gcc make -y
RUN apt-get autoremove -y
RUN rm -rf /var/lib/apt/lists/*
