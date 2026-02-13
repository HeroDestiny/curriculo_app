# 🚀 Guia Rápido - Docker

## Primeira Vez

```powershell
# 1. Configurar ambiente
Copy-Item .env.docker .env

# 2. Iniciar (APP_KEY gerada automaticamente)
docker-compose up -d --build
```

## Acessar

- **App**: http://localhost:8000
- **MailHog**: http://localhost:8025

## Comandos Diários

```powershell
# Iniciar
docker-compose up -d

# Parar
docker-compose down

# Ver logs
docker-compose logs -f

# Acessar shell
docker-compose exec app bash

# Migrations
docker-compose exec app php artisan migrate

# Limpar cache
docker-compose exec app php artisan cache:clear
```

## Resolver Problemas

```powershell
# Resetar tudo (⚠️ apaga dados)
docker-compose down -v
docker-compose up -d --build

# Ver status
docker-compose ps

# Ver logs de um serviço
docker-compose logs -f postgres
docker-compose logs -f redis
```

## Desenvolver

O código em `src/` é montado via volume, então:
- Mudanças no PHP aparecem automaticamente
- Para frontend com hot-reload:
  ```powershell
  cd src
  npm run dev
  ```

---

📚 Documentação completa: [DOCKER.md](DOCKER.md)
