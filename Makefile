.PHONY: help build up down restart logs shell artisan migrate seed fresh test clean

# Variáveis
DOCKER_COMPOSE = docker-compose
APP_CONTAINER = app

help: ## Mostra esta mensagem de ajuda
	@echo "Comandos disponíveis:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Constrói as imagens Docker
	$(DOCKER_COMPOSE) build

up: ## Inicia os containers
	$(DOCKER_COMPOSE) up -d

down: ## Para os containers
	$(DOCKER_COMPOSE) down

restart: down up ## Reinicia os containers

logs: ## Mostra os logs (CTRL+C para sair)
	$(DOCKER_COMPOSE) logs -f

shell: ## Acessa o shell do container da aplicação
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) bash

artisan: ## Executa comando artisan (ex: make artisan cmd="route:list")
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan $(cmd)

migrate: ## Executa as migrações
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate

seed: ## Popula o banco com dados de teste
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan db:seed

fresh: ## Reseta o banco e executa migrações + seeds
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate:fresh --seed

test: ## Executa os testes
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan test

clean: ## Para containers e remove volumes
	$(DOCKER_COMPOSE) down -v

setup: ## Primeira instalação (build + up + migrate + seed)
	$(DOCKER_COMPOSE) up -d --build
	@echo "⏳ Aguardando containers iniciarem..."
	@sleep 10
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate --force
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan db:seed --class=AdminUserSeeder
	@echo "✅ Instalação concluída! Acesse http://localhost:8000"
	@echo "📧 MailHog disponível em http://localhost:8025"
