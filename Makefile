PACKAGE := @docker compose run --rm package

.PHONY:
test:
	$(PACKAGE) composer test

.PHONY:
analyse:
	$(PACKAGE) composer analyse
