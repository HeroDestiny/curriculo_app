FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

# Instalar Node.js 20 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libgd-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar usuário não-root
ARG USERNAME=vscode
RUN usermod -s /bin/bash $USERNAME

# Configurar PHP
RUN echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/docker-php-memory-limit.ini \
    && echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini

USER $USERNAME