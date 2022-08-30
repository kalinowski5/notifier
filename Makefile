test:
	php bin/phpunit
.PHONY: test

phpstan:
	php vendor/bin/phpstan analyse
.PHONY: phpstan

init:
	composer install && bin/console doctrine:database:drop --force --quiet && bin/console doctrine:database:create && bin/console doctrine:schema:create --quiet && bin/console notifier:seed-example-data
.PHONY: init

demo:
	bin/console notifier:send-test-notification
.PHONY: demo
