#!/bin/bash

install_php_dependences() {
	symfony composer install
	echo "[COMPOSER] install php dependences . . . "
}

update_php_dependences() {
	symfony composer update
	echo "[COMPOSER] update php dependences . . . "
}

symfony_server_stop() {
	symfony server:stop
	echo "[SYMFONY SERVER] stop . . . "
}

symfony_server_start() {
	symfony server:start --no-tls --port=8070 -d
	echo "[SYMFONY SERVER] start . . . "
}

symfony_server_log() {
	echo "[SYMFONY SERVER] log . . . "
	symfony server:log
}

update_db() {
	echo "[DB] run migrations . . . "
	symfony console --no-interaction doctrine:migrations:migrate
}

fill_db() {
	echo "[DB] run fixtures . . . "
	symfony console --no-interaction doctrine:fixtures:load
}

recreate_db_test() {
	echo "[DB TEST] CREATE SCHEMA . . . "
	symfony console doctrine:database:drop --if-exists --force --env=test
	symfony console doctrine:database:create --env=test
	symfony console doctrine:schema:update --force --env=test
}

fill_db_test() {
	echo "[DB] run fixtures . . . "
	symfony console --no-interaction --env=test doctrine:fixtures:load
}

case "$1" in
	start)
		install_php_dependences

		update_db

		fill_db

		symfony_server_start

		symfony_server_log
		;;

	test)
		recreate_db_test

		fill_db_test

		./vendor/bin/phpunit
		;;

	psalm)
		./vendor/bin/psalm
		;;

	cs)
		./vendor/bin/phpcs -n
		;;

	cbf)
		./vendor/bin/phpcbf
		;;

	bash)
		/bin/bash
		;;

	*)
		echo "This container accepts the following commands:"
		echo "- start: start web server (Symfony server)"
		echo "- test: run test check (PHPUnit)"
		echo "- psalm: run code static analysis (Psalm)"
		echo "- cs: run coding standard violations detection (PHP_CodeSniffer phpcs)"
		echo "- cbf: run coding standard violations fix (PHP_CodeSniffer phpcbf)"
		echo "- bash: start a shell (usefull to make cli operations)"
		exit 1
esac