FROM php:8.0.8-alpine3.13

# Apk install
RUN apk --no-cache update \
    && apk --no-cache add bash git

# Install pdo
# (Je l'ai oublié dans la vidéo, c'est important car sans ça vous ne pouvez pas vous connecter à votre base de données)
RUN docker-php-ext-install pdo_mysql

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN apk add --update nodejs npm

RUN wget https://cs.symfony.com/download/php-cs-fixer-v3.phar -O php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

WORKDIR /var/www/html

CMD composer install \
    && npm install \
    && symfony server:start