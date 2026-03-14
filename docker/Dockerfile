FROM php:8.2-fpm

# ── Sistema base ──────────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    nodejs \
    npm \
    supervisor \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# ── Extensiones PHP ───────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
      pdo \
      pdo_mysql \
      mbstring \
      gd \
      zip \
      bcmath \
      intl \
      pcntl \
      opcache

# ── Composer ──────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── Directorio de trabajo ─────────────────────────────────────────────────────
WORKDIR /var/www/citas

# ── Dependencias PHP (sin dev) ────────────────────────────────────────────────
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# ── Dependencias JS + build assets ───────────────────────────────────────────
COPY package.json package-lock.json ./
RUN npm ci

# ── Código fuente completo ────────────────────────────────────────────────────
COPY . .

# ── Finalizar instalación PHP ─────────────────────────────────────────────────
RUN composer dump-autoload --optimize

# ── Compilar assets Vite (producción) ────────────────────────────────────────
RUN npm run build && rm -rf node_modules

# ── Permisos ──────────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/citas \
 && chmod -R 775 /var/www/citas/storage \
 && chmod -R 775 /var/www/citas/bootstrap/cache

# ── Configuraciones ───────────────────────────────────────────────────────────
COPY docker/nginx/nginx.conf       /etc/nginx/nginx.conf
COPY docker/nginx/default.conf     /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini            /usr/local/etc/php/conf.d/custom.ini
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh          /entrypoint.sh

RUN chmod +x /entrypoint.sh \
 && rm -f /etc/nginx/sites-enabled/default

EXPOSE 80

CMD ["/entrypoint.sh"]
