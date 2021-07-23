FROM nginx:alpine

COPY nginx.conf /etc/nginx/
COPY templates/* /etc/nginx/templates/

RUN apk --no-cache update \
    && apk --no-cache add \
    	bash \
    	curl \
		php \
    	php7-fpm \
		php7-ctype \
		php7-curl \
		php7-dom \
		php7-gd \
		php7-iconv \
		php7-json \
		php7-intl \
		php7-fileinfo\
		php7-mbstring \
		php7-opcache \
		php7-openssl \
		php7-pdo \
		php7-pdo_mysql \
		php7-mysqli \
		php7-xml \
		php7-xsl \
		php7-zlib \
		php7-phar \
		php7-tokenizer \
		php7-session \
		php7-simplexml \
		php7-zip \
		php7-xmlwriter \
		php7-sodium \
		php7-pecl-apcu



RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

RUN mkdir -p /etc/nginx/templates \
    mkdir -p /tmp/nginx
WORKDIR /var/www/html

RUN  rm /etc/nginx/conf.d/default.conf

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
