all: up

up:
	@docker compose up -d --build --remove-orphans

migrate:
	@docker compose exec --user=www-data php yii migrate --interactive=0

up:
	@docker compose up -d --build --remove-orphans

down:
	@docker compose down

composer-install:
	@docker compose exec --user=www-data php composer install

cp-env:
	@test -f .env || cp .env-dist .env

install: cp-env up composer-install sleep

sleep:
	@sleep 30

make sender:
	@docker compose exec --user=www-data supervisor php console/sender.php

make receive:
	@docker compose exec --user=www-data supervisor php console/receive.php