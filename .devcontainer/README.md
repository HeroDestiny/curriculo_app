# DevContainer - Currículo App

Este devcontainer fornece um ambiente de desenvolvimento completo para o projeto Currículo App (Laravel + Vue.js).

## 🚀 Início Rápido

1. **Abrir no DevContainer**: VS Code detectará automaticamente e oferecerá para reabrir no container
2. **Aguardar configuração**: O devcontainer executará automaticamente as configurações iniciais
3. **Iniciar o projeto**:
   ```bash
   cd src
   php artisan serve --host=0.0.0.0 --port=8000
   ```

## 🛠️ O que está incluído

### Serviços
- **App**: PHP 8.2 + Node.js 20 LTS
- **Database**: MySQL 8.0
- **Cache**: Redis 7

### Extensões VS Code
- PHP Intelephense
- Vue - Official (Volar)
- Tailwind CSS IntelliSense
- Prettier
- TypeScript
- PHP Debug

### Portas Expostas
- `8000`: Laravel Application
- `3000`: Aplicação adicional
- `5173`: Vite Dev Server
- `3306`: MySQL
- `6379`: Redis

## 📝 Comandos Úteis

### Laravel
```bash
# Servidor de desenvolvimento
php artisan serve --host=0.0.0.0 --port=8000

# Migrações
php artisan migrate
php artisan migrate:fresh --seed

# Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Testes
php artisan test
```

### Frontend (Vue.js + Vite)
```bash
# Desenvolvimento
npm run dev

# Build para produção
npm run build

# Linting
npm run lint

# Formatação
npm run format
```

### Banco de Dados
```bash
# Conectar ao MySQL
mysql -h db -u laravel -p curriculo_app
# Senha: password
```

## 🔧 Configuração Manual

Se precisar reconfigurar o projeto manualmente, execute:

```bash
/workspaces/curriculo_app/.devcontainer/setup.sh
```

## 📁 Estrutura do Projeto

O código fonte está localizado em `/workspaces/curriculo_app/src/`

## 🐛 Troubleshooting

### Problema com permissões
```bash
sudo chown -R vscode:vscode /workspaces/curriculo_app
```

### Limpar containers e volumes
```bash
# Parar containers
docker-compose down

# Remover volumes (cuidado: apaga dados do banco)
docker-compose down -v
```

### Problemas com Node.js/npm
```bash
# Limpar cache do npm
npm cache clean --force

# Reinstalar dependências
rm -rf node_modules package-lock.json
npm install
```