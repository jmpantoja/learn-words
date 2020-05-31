ARG NGINX_VERSION=1.15
ARG VARNISH_VERSION=6.0

# build for production
ARG APP_ENV=prod

FROM jmpantoja/php:7.4.6 as planb_php

COPY docker/php/rclone.conf /root/.config/rclone/rclone.conf
COPY docker/php/crontab /srv/cron.crontab

RUN chmod 0644 /srv/cron.crontab
RUN crontab /srv/cron.crontab
RUN service cron start

# prevent the reinstallation of vendors at every changes in the source code
COPY app/composer.json app/composer.lock app/symfony.lock ./
COPY app/modules modules/
COPY app/src src/

FROM planb_php as planb_php_dev

#Ver https://brunopaz.dev/blog/docker-phpstorm-and-xdebug-the-definitive-guide
RUN pecl install xdebug; \
	docker-php-ext-enable xdebug; \
	echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	echo "display_startup_err=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;


FROM nginx:${NGINX_VERSION}-alpine AS planb_nginx
RUN rm /etc/nginx/conf.d/default.conf
COPY docker/nginx/conf.d/ /etc/nginx/conf.d/
WORKDIR /srv/app
COPY --from=planb_php /srv/app/modules/frontend/public modules/frontend/public

FROM cooptilleuls/varnish:${VARNISH_VERSION}-alpine AS planb_varnish
COPY docker/varnish/conf/default.vcl /usr/local/etc/varnish/default.vcl



