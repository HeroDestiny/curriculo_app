#!/bin/bash

echo "🚀 Configurando o projeto Currículo App..."

# Navegar para o diretório src
cd /workspaces/curriculo_app/src

# Copiar .env.example para .env se não existir
if [ ! -f .env ]; then
    echo "📄 Criando arquivo .env..."
    cp .env.example .env
fi

# Instalar dependências PHP
echo "📦 Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader

# Instalar dependências Node.js
echo "📦 Instalando dependências Node.js..."
npm install

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
php artisan key:generate --ansi

# Aguardar banco de dados estar disponível
echo "⏳ Aguardando banco de dados..."
until nc -z db 3306; do
    echo "Aguardando MySQL..."
    sleep 2
done

# Executar migrações
echo "🗄️ Executando migrações..."
php artisan migrate --force

# Executar seeders
echo "🌱 Executando seeders..."
php artisan db:seed --force

# Limpar cache
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Build dos assets para produção
echo "🏗️ Buildando assets..."
npm run build

echo "✅ Configuração concluída!"
echo ""
echo "Para iniciar o projeto:"
echo "  cd src"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "Para desenvolvimento com Vite:"
echo "  npm run dev"