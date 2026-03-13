<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Todas las rutas del consultorio están bajo el prefijo /{tenant}.
| El middleware InitializeTenancyByPath identifica el tenant por el
| primer segmento de la URL y cambia a la base de datos correspondiente.
|
*/

// InitializeTenancyByPath debe ir ANTES de 'web' para que el switch de BD
// ocurra antes de que la sesión/auth intenten resolver usuarios.
Route::middleware([InitializeTenancyByPath::class, 'web'])
    ->prefix('{tenant}')
    ->group(function () {
        // Rutas de Fortify (login, logout, registro, reset password, 2FA, etc.)
        // Se incluyen aquí para que hereden el prefijo /{tenant}
        require base_path('vendor/laravel/fortify/routes/routes.php');

        // Rutas de Jetstream (perfil de usuario)
        require base_path('vendor/laravel/jetstream/routes/livewire.php');

        // Rutas del panel de administración del consultorio
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/admin.php'));
    });
