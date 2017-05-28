FROM nginx:latest

COPY site.conf /etc/nginx/conf.d/site.conf

RUN apt-get update  && apt-get install -y --no-install-recommends \
    sudo \
    nano