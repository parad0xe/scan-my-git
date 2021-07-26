FROM php:8.0.8-alpine3.13

# Apk install
RUN apk --no-cache update \
    && apk --no-cache add \
    	bash \
    	git \
		shadow \
    	php8-fpm \
		php8-ctype \
		php8-curl \
		php8-dom \
		php8-gd \
		php8-iconv \
		php8-json \
		php8-intl \
		php8-fileinfo\
		php8-mbstring \
		php8-opcache \
		php8-openssl \
		php8-pdo \
		php8-pdo_mysql \
		php8-mysqli \
		php8-xml \
		php8-xsl \
		php8-zlib \
		php8-phar \
		php8-tokenizer \
		php8-session \
		php8-simplexml \
		php8-zip \
		php8-xmlwriter \
		php8-sodium \
		php8-pecl-apcu

RUN docker-php-ext-install pdo pdo_mysql

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# NodeJS / NPM
RUN apk add --update nodejs npm

RUN wget https://cs.symfony.com/download/php-cs-fixer-v3.phar -O php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

# Symfony PHP Conf
COPY symfony.ini /etc/php8/conf.d/
COPY symfony.ini /etc/php8/cli/conf.d/
COPY symfony.pool.conf /etc/php8/php-fpm.d/

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

WORKDIR /var/www/html

CMD ["php-fpm8", "-F"]

EXPOSE 9001
