setup:
	cp .env.example .env
	chmod -R 777 ./storage
	docker compose up -d
	docker compose exec php composer install

