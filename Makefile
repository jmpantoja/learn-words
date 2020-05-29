.PHONY: deploy

include ${ENV_FILE}
export

init:
	@echo "init"

deploy:
	chmod +x ./api/bin/console
	./deploy/create_file.sh  ./deploy/tpl/.env > .env
	./deploy/create_file.sh ./deploy/tpl/docker-compose.override.yml > docker-compose.override.yml

varnish-purge:
	docker-compose exec cache-proxy varnishadm "ban req.url ~ /"
