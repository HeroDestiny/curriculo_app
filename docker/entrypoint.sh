#!/bin/bash

echo "🚀 Iniciando aplicação Laravel..."

# Verificar se .env existe
if [ ! -f ".env" ]; then
    echo "⚠️  Arquivo .env não encontrado! Criando arquivo básico..."
    cat > .env << 'EOF'
APP_NAME="Sistema de Curriculos"
APP_ENV=local
APP_KEY=
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=curriculo_app
DB_USERNAME=curriculo_user
DB_PASSWORD=secret

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=dev@curriculos.local
MAIL_FROM_NAME="Sistema de Curriculos"
EOF
fi

# Instalar dependências Composer se não existirem
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "📦 Instalando dependências PHP com Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader 2>&1 | head -20
fi

# Instalar dependências Node.js se não existirem
if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependências Node.js..."
    npm install --silent 2>&1 | tail -5
fi

# Compilar assets do Vite se manifest não existir
if [ ! -f "public/build/manifest.json" ]; then
    echo "🎨 Compilando assets do Vite..."
    npm run build 2>&1 | tail -10
fi

# Verificar se vendor foi instalado com sucesso
if [ ! -f "vendor/autoload.php" ]; then
    echo "❌ Erro: Falha ao instalar dependências do Composer"
    echo "Iniciando servidor sem Laravel (apenas Nginx)..."
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
    exit 0
fi

# Gerar APP_KEY se não existir ou estiver vazia
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Gerando APP_KEY..."
    php artisan key:generate --force --no-interaction || echo "⚠️  Aviso: Não foi possível gerar APP_KEY"
else
    echo "✅ APP_KEY já configurada"
fi

# Esperar o banco de dados estar pronto (com timeout)
echo "⏳ Aguardando banco de dados PostgreSQL..."
COUNTER=0
MAX_TRIES=30
until pg_isready -h postgres -U ${DB_USERNAME:-curriculo_user} -d ${DB_DATABASE:-curriculo_app} 2>/dev/null || [ $COUNTER -eq $MAX_TRIES ]; do
    echo "Tentativa $((COUNTER+1))/$MAX_TRIES - Banco de dados não está pronto..."
    sleep 2
    COUNTER=$((COUNTER+1))
done

if [ $COUNTER -eq $MAX_TRIES ]; then
    echo "⚠️  Aviso: Não foi possível conectar ao banco de dados após $MAX_TRIES tentativas"
    echo "Iniciando aplicação sem executar migrations..."
else
    echo "✅ Banco de dados conectado!"
    
    # Executar migrações
    echo "🔄 Executando migrações..."
    php artisan migrate --force 2>&1 || echo "⚠️  Aviso: Erro ao executar migrations"
    
    # Criar link simbólico para storage (se não existir)
    if [ ! -L "public/storage" ]; then
        echo "🔗 Criando link simbólico de storage..."
        php artisan storage:link 2>&1 || echo "⚠️  Aviso: Erro ao criar link simbólico"
    fi
fi

echo ""
echo "✨ Aplicação pronta! 🎉"
echo "📍 Acesse: http://localhost:8000"
echo "📧 MailHog: http://localhost:8025"
echo ""

# Criar diretórios necessários
mkdir -p /var/log/supervisor /var/run

# Iniciar supervisor (que gerencia PHP-FPM e Nginx)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
