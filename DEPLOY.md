# Guía de Despliegue en Contabo (Docker)

## Arquitectura en el servidor

```
Contabo VPS
│
├── nginx-proxy  (puerto 80/443 → enruta por dominio)
│     ├── → powerbi-app  (dominio: reportes.tu-dominio.com)
│     └── → citas-app    (dominio: citas.tu-dominio.com)
│
├── powerbi-app   [contenedor existente]
├── powerbi-db    [MySQL existente]
│
├── citas-app     [NUEVO]
└── citas-db      [NUEVO MySQL]
```

---

## Prerequisitos en el servidor

```bash
# Verificar Docker y versión
docker --version
docker compose version

# Verificar contenedores existentes
docker ps

# Verificar redes Docker existentes (anotarlas)
docker network ls
```

---

## Paso 1 — Subir el proyecto al servidor

### Opción A: Git (recomendado)

```bash
# En el servidor
cd /opt
git clone https://github.com/tu-usuario/citas.git
cd citas
```

### Opción B: rsync desde tu máquina local

```bash
# Desde Windows (WSL) hacia el servidor
rsync -avz --exclude='node_modules' --exclude='.env' --exclude='vendor' \
  /mnt/c/xampp/htdocs/laravel/citas/ \
  usuario@IP_CONTABO:/opt/citas/
```

---

## Paso 2 — Configurar el .env de producción

```bash
cd /opt/citas

# Copiar el ejemplo y editar
cp docker/.env.production.example .env
nano .env
```

**Valores a completar obligatoriamente:**

| Variable | Descripción |
|---|---|
| `APP_KEY` | Generar con `php artisan key:generate --show` |
| `APP_URL` | URL pública: `https://citas.tu-dominio.com` |
| `DB_PASSWORD` | Contraseña para el usuario MySQL |
| `DB_ROOT_PASSWORD` | Contraseña root MySQL (solo primer arranque) |

### Generar APP_KEY (sin tener PHP local)

```bash
# Usando el contenedor de PHP del servidor existente o:
docker run --rm php:8.2-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

---

## Paso 3 — Verificar red compartida con contenedores existentes

Si el MySQL de citas va a compartir red con la app existente de PowerBI:

```bash
# Ver el nombre de la red actual del PowerBI
docker inspect powerbi-app | grep -i network

# Si existe una red compartida (ej: "shared-net"), usar ese nombre en docker-compose.yml
# Si NO existe, crearla:
docker network create shared-net

# Conectar el contenedor MySQL existente a shared-net (si se va a compartir)
docker network connect shared-net powerbi-db
```

> **Si prefieres MySQL separado** (recomendado para aislamiento), no necesitas hacer nada — el `docker-compose.yml` crea `citas-db` independiente.

---

## Paso 4 — Construir y levantar los contenedores

```bash
cd /opt/citas/docker

# Construir imagen (primera vez, tarda ~5 minutos)
docker compose build --no-cache

# Levantar en segundo plano
docker compose up -d

# Ver logs en tiempo real
docker compose logs -f citas-app
```

### Verificar que todo esté corriendo

```bash
docker compose ps
# Debe mostrar: citas-app, citas-db, nginx-proxy — todos "running"
```

---

## Paso 5 — SSL con Let's Encrypt

### Instalar Certbot en el servidor (si no está)

```bash
apt install certbot -y
```

### Obtener certificado

```bash
# Primero asegúrate de que el dominio apunte a la IP del servidor (DNS)
certbot certonly --webroot \
  -w /var/www/certbot \
  -d citas.tu-dominio.com \
  --email tu@email.com \
  --agree-tos \
  --non-interactive
```

### Copiar certificados al directorio del proxy

```bash
cp /etc/letsencrypt/live/citas.tu-dominio.com/fullchain.pem \
   /opt/citas/docker/nginx-proxy/certs/fullchain.pem

cp /etc/letsencrypt/live/citas.tu-dominio.com/privkey.pem \
   /opt/citas/docker/nginx-proxy/certs/privkey.pem

# Recargar nginx
docker compose exec nginx-proxy nginx -s reload
```

### Renovación automática (cron)

```bash
crontab -e
# Agregar esta línea:
0 3 * * * certbot renew --quiet && \
  cp /etc/letsencrypt/live/citas.tu-dominio.com/fullchain.pem /opt/citas/docker/nginx-proxy/certs/fullchain.pem && \
  cp /etc/letsencrypt/live/citas.tu-dominio.com/privkey.pem /opt/citas/docker/nginx-proxy/certs/privkey.pem && \
  docker exec nginx-proxy nginx -s reload
```

---

## Paso 6 — Sembrar datos iniciales

```bash
# Entrar al contenedor
docker exec -it citas-app bash

# Sembrar la base de datos central
php artisan db:seed --class=SuperAdminSeeder

# Crear el primer tenant (consultorio)
php artisan tenants:create  # o usar el panel super-admin

# Sembrar datos del tenant (catálogos, curvas OMS, etc.)
php artisan tenants:seed --class=TenantDatabaseSeeder

exit
```

---

## Paso 7 — Configurar el nginx del servidor existente

Si ya tienes un nginx-proxy corriendo para el PowerBI, **no uses el `nginx-proxy` del docker-compose de citas**. En cambio, agrega un virtual host al nginx existente:

```bash
# Editar la configuración del proxy existente
nano /ruta/a/tu/nginx-proxy/conf.d/citas.conf
```

Pega el contenido de `docker/nginx-proxy/conf.d/citas.conf` ajustando el dominio, luego:

```bash
docker exec tu-nginx-proxy nginx -s reload
```

---

## Comandos de mantenimiento

### Ver logs

```bash
docker compose -f /opt/citas/docker/docker-compose.yml logs -f citas-app
docker compose -f /opt/citas/docker/docker-compose.yml logs -f citas-db
```

### Actualizar el código (deploy)

```bash
cd /opt/citas

# Bajar cambios
git pull origin main

# Reconstruir y reiniciar solo la app (sin tocar la DB)
docker compose -f docker/docker-compose.yml up -d --build citas-app

# Limpiar imágenes antiguas
docker image prune -f
```

### Ejecutar comandos Artisan

```bash
docker exec -it citas-app php artisan migrate
docker exec -it citas-app php artisan tenants:migrate
docker exec -it citas-app php artisan cache:clear
docker exec -it citas-app php artisan queue:restart
```

### Backup de base de datos

```bash
# Backup completo
docker exec citas-db mysqldump \
  -u root -p"$DB_ROOT_PASSWORD" \
  --all-databases \
  > /opt/backups/citas_$(date +%Y%m%d_%H%M%S).sql

# Restaurar
docker exec -i citas-db mysql \
  -u root -p"$DB_ROOT_PASSWORD" \
  < /opt/backups/citas_20260313_120000.sql
```

### Backup automático diario (cron)

```bash
crontab -e
# Agregar:
0 2 * * * docker exec citas-db mysqldump -u root -p"TU_ROOT_PASSWORD" --all-databases > /opt/backups/citas_$(date +\%Y\%m\%d).sql && find /opt/backups -name "citas_*.sql" -mtime +7 -delete
```

---

## Solución de problemas comunes

### El contenedor no arranca

```bash
docker compose logs citas-app
# Revisar errores de .env, permisos o conexión a DB
```

### Error de conexión a MySQL

```bash
# Verificar que citas-db esté healthy
docker compose ps

# Probar conexión desde la app
docker exec -it citas-app php artisan tinker
# >>> DB::connection()->getPdo()
```

### Assets (CSS/JS) no cargan

```bash
# Verificar que public/build existe
docker exec citas-app ls public/build

# Si no existe, recompilar
docker exec citas-app sh -c "npm ci && npm run build"
```

### Permisos de storage

```bash
docker exec citas-app chown -R www-data:www-data /var/www/citas/storage
docker exec citas-app chmod -R 775 /var/www/citas/storage
```

---

## Estructura de archivos Docker

```
docker/
├── Dockerfile                    # Imagen de la app
├── entrypoint.sh                 # Script de inicio
├── docker-compose.yml            # Orquestación
├── .env.production.example       # Variables de entorno de ejemplo
├── nginx/
│   ├── nginx.conf                # Config principal nginx (dentro del contenedor)
│   └── default.conf              # Virtual host de la app
├── php/
│   └── php.ini                   # Config PHP personalizada
├── supervisor/
│   └── supervisord.conf          # Proceso: php-fpm + nginx + queue worker
└── nginx-proxy/
    ├── conf.d/
    │   └── citas.conf            # Virtual host del proxy externo
    └── certs/                    # Certificados SSL (generados con certbot)
        ├── fullchain.pem
        └── privkey.pem
```
