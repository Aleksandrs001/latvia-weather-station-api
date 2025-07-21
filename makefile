PHP_CONTAINER=weather-php

.PHONY: help install run app-download-weather-data app-import-stations

help:
	@echo "Available make commands:"
	@echo "  install                Install dependencies and build containers"
	@echo "  run                    Run DB migration and import CSV"
	@echo "  app-download-weather-data    Download CSV from source"
	@echo "  app-import-stations          Import stations from CSV to DB"
	@echo "  bash                   Open bash shell inside PHP container"
	@echo "  unit-test              Run unit tests for StationImporter"

install:
	composer install
	docker-compose up -d --build

run:
	docker exec -it $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction && \
	docker exec -it $(PHP_CONTAINER) php bin/console app:download-csv && \
	docker exec -it $(PHP_CONTAINER) php bin/console app:import-csv

app-download-weather-data:
	docker exec -it $(PHP_CONTAINER) php bin/console app:download-csv

app-import-stations:
	docker exec -it $(PHP_CONTAINER) php bin/console app:import-csv

bash:
	docker exec -it $(PHP_CONTAINER) bash

unit-test:
	docker exec -it weather-php ./vendor/bin/phpunit tests/Service/StationImporterTest.php
