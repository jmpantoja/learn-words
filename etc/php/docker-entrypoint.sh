#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
	if [ "$APP_ENV" != 'prod' ]; then
		PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
	fi
	ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

	mkdir -p var/cache var/log
	chmod -R 777 var

#	if [ "$APP_ENV" != 'prod' ]; then
#		composer install --prefer-dist --no-progress --no-suggest --no-interaction --no-scripts
#	fi

#	echo "Waiting for db to be ready..."
#	until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
#		sleep 1
#	done

#	if [ "$APP_ENV" != 'prod' ]; then
#		bin/console doctrine:schema:update --force --no-interaction
#
#		if [ -f dumps/britannia ]; then
#			bin/console doctrine:database:import dumps/britannia.sql
#		fi
#	fi
fi

service cron restart

exec docker-php-entrypoint "$@"
