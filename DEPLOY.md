# Guía de Despliegue — Servidor Contabo

## Estado actual del servidor

```
Contenedores corriendo:
  difiesta-bi-app   → puerto 8080 (expuesto directo)
  difiesta-bi-db    → MySQL 8.0, puerto 3306

Red existente: difiesta-network
```

## Arquitectura objetivo

```
Internet
    │
    ├── puerto 80/443
    │
┌───▼──────────────────────────────────────────┐
│  nginx-proxy  (nuevo)                        │
│  bi.tu-dominio.com   → difiesta-bi-app:80    │
│  citas.tu-dominio.com → citas-app:80         │
└──────────────────────────────────────────────┘
         │                        │
         │ difiesta-network       │ citas-network
         │                        │
  difiesta-bi-app          citas-app (nuevo)
  difiesta-bi-db           citas-db  (nuevo)
```

> El puerto 8080 de difiesta-bi-app quedará solo para acceso interno.
> Todo el tráfico externo pasará por nginx-proxy en 80/443.

---

## Paso 1 — Conectar difiesta-bi-app a la red del proxy

El nginx-proxy necesita llegar a `difiesta-bi-app`. Como el proxy estará
en `difiesta-network`, hay que asegurarse de que la bi-app esté en esa red:

```bash
# Verificar que difiesta-bi-app ya está en difiesta-network
docker inspect difiesta-bi-app | grep -A 10 '"Networks"'
# Si aparece difiesta-network, ya está. Si no:
docker network connect difiesta-network difiesta-bi-app
```

---

## Paso 2 — Subir el proyecto Citas al servidor

```bash
# Opción A: Git
cd /opt
git clone https://github.com/tu-usuario/citas.git
cd citas

# Opción B: rsync desde tu PC (ejecutar en WSL)
rsync -avz \
  --exclude='node_modules' \
  --exclude='.env' \
  --exclude='vendor' \
  --exclude='public/build' \
  /mnt/c/xampp/htdocs/laravel/citas/ \
  root@IP_DEL_SERVIDOR:/opt/citas/
```

---

## Paso 3 — Crear el .env de producción

```bash
cd /opt/citas
cp docker/.env.production.example .env
nano .env
```

**Completar estos valores:**

```env
APP_KEY=           # ver cómo generarlo abajo
APP_URL=https://citas.tu-dominio.com

DB_HOST=citas-db   # nombre del contenedor MySQL de citas
DB_DATABASE=citas_central
DB_USERNAME=citas_user
DB_PASSWORD=UnaContraseñaSegura123
DB_ROOT_PASSWORD=OtraContraseñaRoot456
```

**Generar APP_KEY:**
```bash
docker run --rm php:8.2-cli php -r \
  "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

---

## Paso 4 — Configurar dominios en nginx-proxy

```bash
# Editar los archivos de virtual host con TUS dominios reales
nano /opt/citas/docker/nginx-proxy/conf.d/citas.conf
nano /opt/citas/docker/nginx-proxy/conf.d/difiesta-bi.conf

# Reemplazar "tu-dominio.com" por el dominio real en ambos archivos
# Ejemplo: citas.clinica-xyz.com y bi.clinica-xyz.com
```

---

## Paso 5 — Levantar SSL (antes de subir el proxy con HTTPS)

Primero levantamos nginx-proxy solo con HTTP para que certbot pueda
verificar el dominio:

```bash
cd /opt/citas/docker

# Crear carpeta de certificados
mkdir -p nginx-proxy/certs

# Levantar SOLO el proxy temporalmente en modo HTTP
# Comentar los bloques "listen 443" en ambos .conf primero:
sed -i 's/return 301/# return 301/' nginx-proxy/conf.d/*.conf

docker compose up -d nginx-proxy

# Obtener certificado para citas
docker compose run --rm certbot certonly \
  --webroot \
  --webroot-path=/var/www/certbot \
  -d citas.tu-dominio.com \
  --email tu@email.com \
  --agree-tos \
  --non-interactive

# Obtener certificado para BI (si tiene dominio)
docker compose run --rm certbot certonly \
  --webroot \
  --webroot-path=/var/www/certbot \
  -d bi.tu-dominio.com \
  --email tu@email.com \
  --agree-tos \
  --non-interactive

# Restaurar los redirects HTTPS
sed -i 's/# return 301/return 301/' nginx-proxy/conf.d/*.conf

# Reiniciar proxy con HTTPS activo
docker compose restart nginx-proxy
```

---

## Paso 6 — Levantar todo

```bash
cd /opt/citas/docker

# Construir imagen de citas (primera vez: ~5 min)
docker compose build --no-cache citas-app

# Levantar todos los servicios
docker compose up -d

# Verificar que todos están corriendo
docker compose ps
```

Salida esperada:
```
NAME            STATUS          PORTS
citas-app       Up
citas-db        Up (healthy)
nginx-proxy     Up              0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp
certbot         Up
```

---

## Paso 7 — Sembrar datos iniciales

```bash
# Entrar al contenedor
docker exec -it citas-app bash

# Crear super-admin
php artisan db:seed --class=SuperAdminSeeder

# Salir
exit
```

Luego entrar al panel super-admin en `https://citas.tu-dominio.com/super-admin`
para crear el primer tenant (consultorio).

Después sembrar catálogos del tenant:

```bash
docker exec -it citas-app php artisan tenants:seed --class=TenantDatabaseSeeder
```

---

## Paso 8 — Deshabilitar el puerto 8080 expuesto de difiesta-bi-app

Una vez que el proxy funcione, el puerto 8080 ya no debe estar expuesto
directamente. Para hacerlo, editar el docker-compose.yml de la app BI
y quitar el `ports: - "8080:80"`, luego reiniciar esa app.

```bash
# Verificar que BI funciona a través del proxy ANTES de hacer esto
curl -I https://bi.tu-dominio.com

# Si funciona, quitar el puerto expuesto del contenedor BI
# (editar el docker-compose.yml de difiesta-bi y reiniciar)
```

---

## Comandos de mantenimiento

### Actualizar código (deploy)

```bash
cd /opt/citas

# Bajar cambios
git pull origin main

# Reconstruir y reiniciar solo la app
docker compose -f docker/docker-compose.yml up -d --build citas-app

# Limpiar imágenes antiguas
docker image prune -f
```

### Comandos Artisan

```bash
docker exec -it citas-app php artisan migrate
docker exec -it citas-app php artisan tenants:migrate
docker exec -it citas-app php artisan cache:clear
docker exec -it citas-app php artisan config:cache
docker exec -it citas-app php artisan queue:restart
```

### Ver logs en tiempo real

```bash
docker compose -f /opt/citas/docker/docker-compose.yml logs -f citas-app
docker compose -f /opt/citas/docker/docker-compose.yml logs -f nginx-proxy
```

### Backup de base de datos

```bash
# Crear directorio de backups
mkdir -p /opt/backups

# Backup manual
docker exec citas-db sh -c \
  'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" --all-databases' \
  > /opt/backups/citas_$(date +%Y%m%d_%H%M%S).sql

# Backup automático diario a las 2am (crontab -e)
0 2 * * * docker exec citas-db sh -c 'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" --all-databases' > /opt/backups/citas_$(date +\%Y\%m\%d).sql && find /opt/backups -name "citas_*.sql" -mtime +7 -delete
```

### Restaurar backup

```bash
docker exec -i citas-db sh -c \
  'mysql -u root -p"$MYSQL_ROOT_PASSWORD"' \
  < /opt/backups/citas_20260313.sql
```

---

## Solución de problemas

### El contenedor no arranca

```bash
docker compose -f docker/docker-compose.yml logs citas-app
# Los errores más comunes: .env mal configurado, DB no disponible
```

### Error 502 Bad Gateway en el proxy

```bash
# Verificar que citas-app está corriendo y responde internamente
docker exec nginx-proxy curl -I http://citas-app:80

# Verificar que difiesta-bi-app responde
docker exec nginx-proxy curl -I http://difiesta-bi-app:80
```

### Certificado SSL no encontrado

```bash
# Verificar que los certificados existen
ls /opt/citas/docker/nginx-proxy/certs/live/

# Si falta alguno, correr certbot de nuevo
docker compose -f docker/docker-compose.yml run --rm certbot certonly \
  --webroot --webroot-path=/var/www/certbot \
  -d citas.tu-dominio.com --email tu@email.com --agree-tos
```

### Assets CSS/JS no cargan (404)

```bash
# Verificar que public/build existe dentro del contenedor
docker exec citas-app ls public/build

# Si no existe, el build falló durante la construcción de imagen
# Revisar logs de docker build:
docker compose -f docker/docker-compose.yml build --no-cache --progress=plain citas-app 2>&1 | tail -50
```

### Permisos de storage

```bash
docker exec citas-app chown -R www-data:www-data /var/www/citas/storage
docker exec citas-app chmod -R 775 /var/www/citas/storage
```
