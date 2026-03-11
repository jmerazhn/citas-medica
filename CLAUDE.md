# CLAUDE.md

Este archivo proporciona orientación a Claude Code (claude.ai/code) cuando trabaja con el código de este repositorio.

## Descripción del proyecto

Sistema de gestión de citas médicas construido con Laravel 12. La aplicación administra pacientes, doctores, recepcionistas y administradores. La funcionalidad de reserva de citas aún no está implementada a pesar del nombre del proyecto.

Zona horaria: `America/Tegucigalpa` (Honduras). El texto de la interfaz está en español.

## Comandos comunes

```bash
# Configuración completa desde cero
composer setup

# Desarrollo (ejecuta servidor Laravel + cola de trabajos + Vite de forma concurrente)
composer dev

# Ejecutar pruebas (limpia la caché de configuración primero)
composer test

# Ejecutar un único archivo de prueba
php artisan test tests/Feature/ExampleTest.php

# Compilar assets del frontend
npm run build      # producción
npm run dev        # modo watch

# Base de datos
php artisan migrate
php artisan db:seed
```

## Arquitectura

### Autenticación y autorización

Stack de autenticación en tres capas:
- **Jetstream 5.4** — Scaffolding de UI (login, registro, perfil, 2FA, gestión de sesiones)
- **Fortify** — Lógica de autenticación en el backend (reglas de contraseña, creación de usuarios en `app/Actions/Fortify/`)
- **Sanctum** — Autenticación por token para API (rutas protegidas con `auth:sanctum`)
- **Spatie Permission 6.24** — Control de acceso basado en roles (RBAC) con cuatro roles sembrados: `Paciente`, `Doctor`, `Recepcionista`, `Administrador`

Los roles con IDs 1–3 están protegidos contra edición/eliminación en `RoleController`. Cuando a un usuario se le asigna el rol "Paciente", se crea automáticamente un registro `Patient` para él.

### Modelos y relaciones

- `User` — Extiende los traits de Jetstream; tiene un `Patient`; usa roles/permisos de Spatie
- `Patient` — Perfil médico (tipo de sangre, alergias, condiciones, contactos de emergencia); pertenece a `User` y `BloodType`
- `BloodType` — Enumeración sembrada (A+, A-, B+, B-, AB+, AB-, O+, O-)

### Controladores y Livewire

Los controladores están en `app/Http/Controllers/Admin/`. Cada recurso (Usuarios, Pacientes, Roles) tiene:
- Un controlador de recursos para operaciones CRUD
- Un componente de tabla Livewire en `app/Livewire/Admin/` (ordenable y paginado con `rappasoft/laravel-livewire-tables`)

### Rutas

- `/` redirige a `/admin`
- Las rutas de administración están definidas en `routes/admin.php`, cargadas con el prefijo `/admin`
- Recursos RESTful: `/admin/users`, `/admin/patients`, `/admin/roles`

### Stack del frontend

- Plantillas **Blade** en `resources/views/`; layouts: `AdminLayout`, `AppLayout`, `GuestLayout`
- **Livewire 3** para componentes reactivos (sin framework JS separado)
- **Tailwind CSS 3.4** + **Flowbite 4** + **WireUI 2.5** para componentes de UI
- **Vite** compila `resources/css/app.css` y `resources/js/app.js` → `public/build/`

### Pruebas

Usa **Pest 3.8** con el plugin de Laravel. Las pruebas se ejecutan contra una base de datos SQLite en memoria (`phpunit.xml`).

### Base de datos

MySQL, nombre de base de datos `citas`. Las sesiones, caché y colas usan el driver de base de datos.

Usuario de prueba sembrado por defecto: `josuemeraz7@gmail.com` / `password`
