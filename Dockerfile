ARG NGINX_VERSION=1.15
ARG VARNISH_VERSION=6.0

# build for production
ARG APP_ENV=prod

FROM jmpantoja/php7.4.6:1.0 as planb_php

COPY build/docker/php/rclone.conf /root/.config/rclone/rclone.conf
COPY build/docker/php/crontab /srv/cron.crontab

RUN chmod 0644 /srv/cron.crontab
RUN crontab /srv/cron.crontab
RUN service cron start

# prevent the reinstallation of vendors at every changes in the source code
COPY app/composer.json app/composer.lock app/symfony.lock ./
COPY app/modules modules/
COPY app/src src/


FROM nginx:${NGINX_VERSION}-alpine AS planb_nginx
RUN rm /etc/nginx/conf.d/default.conf
COPY build/docker/nginx/conf.d/ /etc/nginx/conf.d/
WORKDIR /srv/app
COPY --from=planb_php /srv/app/modules/frontend/public modules/frontend/public

FROM cooptilleuls/varnish:${VARNISH_VERSION}-alpine AS planb_varnish
COPY build/docker/varnish/conf/default.vcl /usr/local/etc/varnish/default.vcl



