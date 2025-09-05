# Portal de Currículos

Sistema completo de gestão de currículos desenvolvido com Laravel 11 e Vue 3, permitindo o envio, visualização e gerenciamento de currículos com interface moderna.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e npm
- MySQL/SQLite
- Extensões PHP: fileinfo, mbstring, openssl, PDO, Tokenizer, XML

## Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/HeroDestiny/curriculo_app.git
cd curriculo_app/src
```

### 2. Instale as dependências PHP

```bash
composer install
```

### 3. Instale as dependências JavaScript

```bash
npm install
```

### 4. Configure o ambiente

Copie o arquivo de exemplo e configure suas variáveis:

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure:

```env
APP_NAME="Portal de Currículos"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Para MySQL, use:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=curriculo_app
# DB_USERNAME=root
# DB_PASSWORD=

# Configuração de E-mail (opcional para desenvolvimento)
MAIL_MAILER=log
ADMIN_EMAIL_1=admin@exemplo.com
ADMIN_EMAIL_2=rh@exemplo.com
```

### 5. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 6. Execute as migrações e seeders

```bash
php artisan migrate --seed
```

### 7. Crie o link simbólico para storage

```bash
php artisan storage:link
```

## Executando o Projeto

### Método 1: Desenvolvimento Local

Execute os comandos em terminais separados:

**Terminal 1 - Servidor Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Build Assets (Vite):**
```bash
npm run dev
```

Acesse: http://localhost:8000

### Método 2: Build de Produção

```bash
npm run build
php artisan serve
```

## Funcionalidades

### Para Candidatos
- Envio de currículo com validações rigorosas
- Formulário intuitivo com máscaras
- Upload de arquivos PDF, DOC, DOCX (máx. 1MB)
- Página de confirmação de envio

### Para Administradores
- Dashboard com listagem de currículos
- Sistema de busca e paginação
- Visualização detalhada dos dados
- Download de arquivos
- Notificações por e-mail

### Validações Implementadas
- Campos obrigatórios com validação customizada
- E-mail único no sistema
- Telefone brasileiro com DDD válido
- Arquivos com verificação de tipo MIME
- Limite de tamanho de arquivo (1MB)
- Registro de IP e timestamp

## Estrutura do Projeto

```
src/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Http/Requests/       # Form Requests
│   ├── Mail/               # Classes de E-mail
│   ├── Models/             # Modelos Eloquent
│   └── Rules/              # Regras de validação customizadas
├── database/
│   ├── factories/          # Factories para testes
│   ├── migrations/         # Migrações do banco
│   └── seeders/           # Seeders
├── resources/
│   ├── js/                # Frontend Vue 3
│   └── views/             # Templates Blade
└── routes/                # Definição de rotas
```

## Comandos Úteis

### Limpar caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Gerar dados de teste
```bash
php artisan db:seed --class=CurriculoSeeder
```

### Verificar logs
```bash
php artisan tinker
```

### Executar testes
```bash
php artisan test
```

## Configuração de E-mail

Para produção, configure um servidor de e-mail real no `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_de_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seudominio.com
MAIL_FROM_NAME="Portal de Currículos"
```

## Tecnologias Utilizadas

### Backend
- Laravel 11
- PHP 8.2+
- SQLite/MySQL
- Laravel Sanctum (autenticação)

### Frontend
- Vue 3 + TypeScript
- Inertia.js
- Tailwind CSS
- shadcn-vue components
- Lucide Icons
- Vite

### Ferramentas
- Laravel Pint (code style)
- Prettier (formatação)
- ESLint (linting)

##