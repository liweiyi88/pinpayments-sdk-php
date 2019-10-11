PHPSTAN=$(EXEC) vendor/bin/phpstan analyse src tests --level max
PHPCS=$(EXEC) vendor/bin/phpcs --standard=PSR12 src tests

ci: phpcs phpstan

phpstan:
	$(PHPSTAN)

phpcs:
	$(PHPCS)
