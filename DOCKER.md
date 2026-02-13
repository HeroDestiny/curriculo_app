# 🐳 Guia Docker - Sistema de Currículos (Ambiente de Desenvolvimento)

Este guia explica como executar a aplicação Laravel de gestão de currículos usando Docker para desenvolvimento local.

## 📋 Pré-requisitos

- Docker instalado (versão 20.10 ou superior)
- Docker Compose instalado (versão 2.0 ou superior)

## 🚀 Início Rápido

### 1. Configurar variáveis de ambiente

Copie o arquivo de exemplo:

```bash
cp .env.docker .env
```

> ℹ️ A `APP_KEY` será gerada automaticamente na primeira inicialização

### 2. Construir e iniciar os containers

```bash
docker-compose up -d --build
```

Este comando irá:
- Construir a imagem da aplicação
- Baixar e iniciar PostgreSQL, Redis e MailHog
- Executar as migrações automaticamente
- Otimizar a aplicação

### 4. Acessar a aplicação

- **Aplicação**: http://localhost:8000
- **MailHog** (visualizar e-mails): http://localhost:8025

> 💡 A `APP_KEY` é gerada automaticamente na primeira execução. Verifique os logs com `docker-compose logs app` para confirmar.

## 🛠️ Comandos Úteis

### Ver logs dos containers

```bash
# Todos os logs
docker-compose logs -f

# Logs apenas da aplicação
docker-compose logs -f app

# Logs do PostgreSQL
docker-compose logs -f postgres
```

### Executar comandos Artisan

```bash
# Dentro do container
docker-compose exec app php artisan migrate

# Criar usuário admin
docker-compose exec app php artisan db:seed --class=AdminUserSeeder

# Limpar cache
docker-compose exec app php artisan cache:clear

# Ver rotas
docker-compose exec app php artisan route:list
```

### Acessar o terminal do container

```bash
docker-compose exec app bash
```

### Parar os containers

```bash
docker-compose down
```

### Parar e remover volumes (⚠️ apaga dados)

```bash
docker-compose down -v
```

### Reconstruir a aplicação após mudanças

```bash
docker-compose up -d --build
```

## 📦 Serviços Incluídos

### App (Laravel + Nginx)
- **Porta**: 8000
- **PHP**: 8.2
- **Nginx**: Alpine
- Inclui todas as extensões PHP necessárias

### PostgreSQL
- **Porta**: 5432
- **Versão**: 16-alpine
- **Credenciais**: Configuráveis no `.env`
- Volume persistente para dados

### Redis
- **Porta**: 6379
- **Versão**: 7-alpine
- Usado para cache e sessões
- Volume persistente

### MailHog
- **Porta SMTP**: 1025
- **Porta Web UI**: 8025
- Captura todos os e-mails para desenvolvimento
- Acesse http://localhost:8025 para ver os e-mails

## 🔧 Desenvolvimento

Para desenvolvimento local com hot-reload:

1. Mantenha os containers rodando:
```bash
docker-compose up -d
```

2. Faça mudanças no código na pasta `src/`

3. Para mudanças no frontend (Vue/TypeScript):
```bash
cd src
npm install
npm run dev
```

4. Os arquivos são sincronizados automaticamente via volumes

## 🐛 Resolução de Problemas

### Container não inicia

Verifique os logs:
```bash
docker-compose logs app
```

### Erro de permissão

Execute dentro do container:
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Banco de dados não conecta

Verifique se o PostgreSQL está saudável:
```bash
docker-compose ps
```

Reinicie o container do banco:
```bash
docker-compose restart postgres
```

### Limpar tudo e começar do zero

```bash
docker-compose down -v
docker-compose up -d --build
```

##  Estrutura de Arquivos Docker

```
.
├── Dockerfile                      # Imagem da aplicação
├── docker-compose.yml             # Orquestração dos serviços
├── .dockerignore                  # Arquivos ignorados no build
├── .env.docker                    # Exemplo de variáveis de ambiente
└── docker/
    ├── nginx/
    │   ├── nginx.conf            # Configuração principal do Nginx
    │   └── default.conf          # Virtual host do Laravel
    ├── supervisor/
    │   └── supervisord.conf      # Gerenciamento de processos
    └── entrypoint.sh             # Script de inicialização
```

## 💡 Dicas

- Use `docker-compose exec app` para executar comandos dentro do container
- Os dados do PostgreSQL são persistidos no volume `postgres_data`
- Os logs do Nginx estão em `/var/log/nginx/` dentro do container
- Para melhor performance em Windows, considere usar WSL2
- O MailHog captura todos os e-mails enviados - acesse em http://localhost:8025
- O código é montado via volume, então mudanças são refletidas imediatamente sem rebuild

## 🆘 Suporte

Em caso de problemas, verifique:
1. Logs dos containers: `docker-compose logs`
2. Status dos serviços: `docker-compose ps`
3. Conexão com banco: `docker-compose exec app php artisan db:show`

---

Desenvolvido com ❤️ usando Laravel 12 e Vue 3
