#!/bin/bash
set -e

echo "==> Iniciando contenedor citas..."

# Cachear configuración Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones centrales
php artisan migrate --force

# Ejecutar migraciones de todos los tenants
php artisan tenants:migrate --force

# Iniciar supervisor (nginx + php-fpm + queue worker)
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
