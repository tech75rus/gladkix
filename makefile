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

init-dev:
	@bash scripts/env-init.sh dev

init-prod:
	@bash scripts/env-init.sh prod
