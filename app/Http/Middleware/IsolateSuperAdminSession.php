<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Aísla la sesión del super-admin usando una cookie distinta.
 *
 * Problema: DatabaseSessionHandler llama a Auth::guard('web')->id() al guardar
 * la sesión. Si el super-admin también inició sesión en un tenant (misma cookie),
 * el guard 'web' intenta buscar el usuario en la tabla `users` de la BD central,
 * que no existe → 500.
 *
 * Solución: usar una cookie de sesión diferente para rutas /super-admin,
 * de modo que esa sesión nunca contenga el token de autenticación del tenant.
 */
class IsolateSuperAdminSession
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (str_starts_with($request->getPathInfo(), '/super-admin')) {
            Config::set('session.cookie', 'super_admin_session');
        }

        return $next($request);
    }
}
