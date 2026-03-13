<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Livewire\Livewire;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Fortify y Jetstream registran sus rutas automáticamente.
        // Las desactivamos para registrarlas manualmente bajo el prefijo /{tenant}.
        Fortify::ignoreRoutes();
        Jetstream::ignoreRoutes();

        // Livewire envía sus requests AJAX a /livewire/update (sin prefijo tenant).
        // Lo reemplazamos con /{tenant}/livewire/update para que tenancy inicialice
        // la BD correcta en cada request de componente Livewire.
        // Debe estar en register() para que ocurra antes de que Livewire registre su ruta default.
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/{tenant}/livewire/update', $handle)
                ->middleware([InitializeTenancyByPath::class, 'web'])
                ->name('livewire.update');
        });
    }

    public function boot(): void
    {

        // Redirigir al panel del tenant después del login.
        // Registrado en boot() para sobreescribir el binding default de Fortify.
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    if (tenancy()->initialized) {
                        return redirect()->route('admin.dashboard', ['tenant' => tenant('id')]);
                    }
                    return redirect('/');
                }
            };
        });

        // Redirigir al login del tenant después del logout
        $this->app->singleton(LogoutResponse::class, function () {
            return new class implements LogoutResponse {
                public function toResponse($request)
                {
                    if (tenancy()->initialized) {
                        return redirect()->route('login', ['tenant' => tenant('id')]);
                    }
                    return redirect('/');
                }
            };
        });

        // Redirigir al panel del tenant después del login con 2FA
        $this->app->singleton(TwoFactorLoginResponse::class, function () {
            return new class implements TwoFactorLoginResponse {
                public function toResponse($request)
                {
                    if (tenancy()->initialized) {
                        return redirect()->route('admin.dashboard', ['tenant' => tenant('id')]);
                    }
                    return redirect('/');
                }
            };
        });
    }
}
