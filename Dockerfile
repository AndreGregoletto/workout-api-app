# Dockerfile
FROM php:8.3-fpm

# Instala dependências do sistema e extensões PHP necessárias para Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Diretório de trabalho
WORKDIR /var/www/html

# Copia o composer.json (se já tiver) - se não tiver, você usará composer create-project depois
# COPY composer.json composer.lock /var/www/html/

# Permissões (ajustes)
RUN useradd -G www-data,root -u 1000 -d /home/developer developer
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R developer:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER developer

# Expõe apenas para comunicação interna; nginx fará proxy
EXPOSE 9000

# Entrypoint (mantém o processo do php-fpm em foreground)
CMD ["php-fpm"]
