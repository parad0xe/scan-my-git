FROM nginx:alpine

COPY nginx.conf /etc/nginx/
COPY templates/* /etc/nginx/templates/

RUN apk --no-cache update \
    && apk --no-cache add \
    	bash \
    	curl 

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

RUN mkdir -p /etc/nginx/templates \
    mkdir -p /tmp/nginx

RUN  rm /etc/nginx/conf.d/default.conf

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
