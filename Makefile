PHPSTAN=$(EXEC) vendor/bin/phpstan analyse src tests --level max
PHPCS=$(EXEC) vendor/bin/phpcs --standard=PSR12 src tests
PHPUNIT=$(EXEC) vendor/bin/phpunit tests/Unit

ci: phpcs phpstan phpunit

phpstan:
	$(PHPSTAN)

phpcs:
	$(PHPCS)

phpunit:
	$(PHPUNIT)
