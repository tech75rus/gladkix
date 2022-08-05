FROM nginx:stable

RUN apt-get update && apt-get install -y software-properties-common python3-certbot-nginx

ADD ./docker/nginx/prod/conf.d /etc/nginx/conf.d

ADD ./docker/nginx/prod/ssl-params.conf /etc/nginx/snippets/

