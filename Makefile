PHPSTAN=$(EXEC) vendor/bin/phpstan analyse src tests --level max
PHPCS=$(EXEC) vendor/bin/phpcs --standard=PSR12 src tests
UNITTEST=$(EXEC) vendor/bin/phpunit tests/Unit
PHPUNIT=$(EXEC) vendor/bin/phpunit tests

qa: phpcs phpstan
unit-tests: unit-test
full-tests: phpunit

phpstan:
	$(PHPSTAN)

phpcs:
	$(PHPCS)

unit-test:
	$(UNITTEST)

phpunit:
	$(PHPUNIT)
