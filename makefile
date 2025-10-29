up:
	docker compose up -d

down:
	docker compose down

ps:
	docker compose ps -a

exec-nginx:
	docker compose exec nginx sh

exec-backend:
	docker compose exec backend bash

exec-db:
	docker compose exec db sh
