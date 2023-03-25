FROM php:8.2.2-fpm

# Instalar dependencias do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nano

WORKDIR /var/www/api

COPY src/api /var/www/api/

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões php fundamentais
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar a ultima versão do composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


