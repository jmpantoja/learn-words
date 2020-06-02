.PHONY: build check-env up composer-install composer-update varnish-purge

SHELL := /bin/bash

check-env:
#ifndef / endif no llevan identación
ifndef env
	$(error env is undefined)
endif

dump: check-env
	touch build/docker/php/rclone.conf
	./build/create_file.sh  $(env) ./build/tpl/.env > .env
	./build/create_file.sh $(env) ./build/tpl/$(env)/docker-compose.override.yml > docker-compose.override.yml

build: dump
	docker-compose build

up: composer-install
	docker-compose up -d

down:
	docker-compose down

composer-install: dump
	docker-compose run php composer install
	docker-compose run php modules/frontend/bin/console cache:clear --env=${env}
composer-update:
	docker-compose run php composer update

xdebug-disabled:
	docker-compose exec php sed -i 's/remote_enable=1/remote_enable=0/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart php

xdebug-enabled:
	docker-compose exec php sed -i 's/remote_enable=0/remote_enable=1/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart php

terraform-plan: check-env
	source ./build/vars/${env}.sh && cd build/terraform && terraform plan

terraform-apply: check-env
	source ./build/vars/${env}.sh && cd build/terraform && terraform apply

terraform-destroy: check-env
	source ./build/vars/${env}.sh && cd build/terraform && terraform destroy


varnish-purge:
	docker-compose exec cache-proxy varnishadm "ban req.url ~ /"


