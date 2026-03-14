<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Rutas del panel super-admin (base de datos central)
            Route::middleware(['web', 'auth:super-admin'])
                ->prefix('super-admin')
                ->name('super-admin.')
                ->group(base_path('routes/super-admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Aislar sesión del super-admin para evitar conflicto con la BD central
        $middleware->prepend(\App\Http\Middleware\IsolateSuperAdminSession::class);

        // Confiar en el proxy reverso (nginx del host) para detectar HTTPS correctamente
        $middleware->trustProxies(at: '*', headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO);

        // InitializeTenancyByPath debe tener mayor prioridad que StartSession.
        // Así el switch de BD ocurre antes de que la sesión/auth intenten resolver usuarios.
        $middleware->priority([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Stancl\Tenancy\Middleware\InitializeTenancyByPath::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
            \Illuminate\Contracts\Session\Middleware\AuthenticatesSessions::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authorize::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No tienes permiso para realizar esta acción.'], 403);
            }
            $tenantId = tenancy()->initialized ? tenant('id') : null;
            $redirect = $tenantId
                ? redirect()->route('admin.dashboard', ['tenant' => $tenantId])
                : redirect('/');
            return $redirect->with('swal', [
                'icon' => 'error',
                'title' => 'Acceso denegado',
                'text' => 'No tienes permiso para acceder a esa sección.',
            ]);
        });
    })->create();
