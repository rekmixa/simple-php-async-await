up:
	@docker-compose up -d --build --remove-orphans --force-recreate

install: docker-compose-override dotenv-config up composer-install composer-dump-autoload

down:
	@docker-compose down

down-v:
	@docker-compose down -v

stop:
	@docker-compose stop

restart:
	@docker-compose restart

env:
	@docker-compose exec --user=www-data php bash

env-root:
	@docker-compose exec php bash

docker-compose-override:
	@test -f docker-compose.override.yml || echo "version: '3'" >> docker-compose.override.yml

dotenv-config:
	@test -f .env || cp .env-dist .env

composer-install:
	@docker-compose run --rm php composer install

composer-dump-autoload:
	@docker-compose run --rm php composer dump-autoload

rm-git:
	@rm -rf .git
