<?php

namespace App\Http\Middleware\Seguridad;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

class AutorizarModulo
{
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        $usuario = $request->user();

        if ($usuario === null) {
            throw new AuthenticationException();
        }

        $permiso = $this->accion($request->route()) . ' ' . mb_strtolower($modulo);

        if (! $usuario->can($permiso)) {
            throw new AuthorizationException('No tienes el permiso "' . $permiso . '".');
        }

        return $next($request);
    }

    private function accion(Route $ruta): string
    {
        if (! str_contains($ruta->getActionName(), '@')) {
            return 'ver';
        }

        $metodo = $ruta->getActionMethod();

        if ($metodo === '__invoke') {
            return 'ver';
        }

        return config('seguridad.metodos_a_acciones')[$metodo] ?? $metodo;
    }
}
