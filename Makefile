.PHONY: build check-env up composer-install composer-update varnish-purge

SHELL := /bin/bash

check-env:
#ifndef / endif no llevan identación
ifndef env
	$(error env is undefined)
endif

dump: check-env
	touch etc/php/rclone.conf
	./build/template.py  $(env) .env > .env
	./build/template.py  $(env) .env.local > app/.env.local
	./build/template.py $(env) $(env)/docker-compose.override.yml > docker-compose.override.yml
	./build/template.py $(env) site.conf > ./etc/nginx/conf.d/default.conf

#	./build/sites.py $(env) ./etc/nginx/conf.d

build: dump
	docker-compose build

up: composer-install
	docker-compose up -d

down:
	docker-compose down

restart: composer-install
	docker-compose restart

composer-install: dump
	docker-compose run php composer install
	docker-compose run php bin/console cache:clear --env=${env}
composer-update:
	docker-compose run php composer update

schema-create: dump
	docker-compose exec php bin/console doctrine:schema:create --env=${env}

schema-update:
	docker-compose exec php bin/console doctrine:schema:update --env=${env} --force

xdebug-disabled:
	docker-compose exec php sed -i 's/remote_enable=1/remote_enable=0/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart php

xdebug-enabled:
	docker-compose exec php sed -i 's/remote_enable=0/remote_enable=1/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart php


terraform-plan: check-env
	./build/template.py  $(env) .terraform > .terraform
	source .terraform && cd build/terraform && terraform plan
	@rm .terraform

terraform-apply: check-env
	./build/template.py  $(env) .terraform > .terraform
	source .terraform cd build/terraform && terraform apply
	@rm .terraform

terraform-destroy: check-env
	./build/template.py  $(env) .terraform > .terraform
	source .terraform cd build/terraform && terraform destroy
	@rm .terraform

varnish-purge:
	docker-compose exec varnish varnishadm "ban req.url ~ /"

nginx-reload: varnish-purge
	docker-compose exec nginx nginx -s reload


