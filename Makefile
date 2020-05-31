.PHONY: build check-env up composer-install composer-update varnish-purge



#COSAS QUE TENGO QUE HACER
#==================================================================
#- en lugar de pasar ENV_FILE voy a pasar solo ENV, y monto la ruta al fichero con vars desde el fichero create_file
#- el source "env_vars.sh" tambien tengo que hacerlo desde el fichero create file
#- ALGO ASI: make up ENV=dev
#- completar con más acciones

check-env:
#ifndef / endif no llevan identación
ifndef env
	$(error env is undefined)
endif

build: check-env
	touch docker/php/rclone.conf
	./deploy/create_file.sh  $(env) ./deploy/tpl/.env > .env
	./deploy/create_file.sh $(env) ./deploy/tpl/$(env)/docker-compose.override.yml > docker-compose.override.yml
	docker-compose build

up: composer-install
	docker-compose up -d

down:
	docker-compose down

composer-install: check-env
	docker-compose run php composer install
	docker-compose run php modules/frontend/bin/console cache:clear --env=${env}
composer-update:
	docker-compose run php composer update

xdebug-off:
	docker-compose exec php sed -i 's/remote_enable=1/remote_enable=0/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart php

xdebug-on:
	docker-compose exec php sed -i 's/remote_enable=0/remote_enable=1/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart php

varnish-purge:
	docker-compose exec cache-proxy varnishadm "ban req.url ~ /"


