<?php

namespace App\Console\Commands\Seguridad;

use App\Http\Middleware\Seguridad\AutorizarModulo;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use ReflectionFunction;

class AuditarRutas extends Command
{
    protected $signature = 'seguridad:auditar {--todas : Incluye también las rutas que ya están protegidas}';

    protected $description = 'Muestra qué rutas de la aplicación no exigen sesión o permiso';

    public function handle(Router $router): int
    {
        $filas = [];
        $pendientes = 0;

        foreach ($router->getRoutes() as $ruta) {
            if (! $this->esDeLaAplicacion($ruta)) {
                continue;
            }

            $middleware = $router->gatherRouteMiddleware($ruta);

            if ($this->tiene($middleware, [RedirectIfAuthenticated::class])) {
                continue;
            }

            $conSesion = $this->tiene($middleware, [Authenticate::class], ['auth']);
            $conPermiso = $this->tiene($middleware, [Authorize::class, AutorizarModulo::class], ['can:']);
            $protegida = $conSesion && $conPermiso;

            if (! $protegida) {
                $pendientes++;
            }

            if ($protegida && ! $this->option('todas')) {
                continue;
            }

            $metodos = collect($ruta->methods())->reject(fn ($metodo) => $metodo === 'HEAD')->implode('|');

            $filas[] = [
                $metodos,
                '/' . ltrim($ruta->uri(), '/'),
                $ruta->getName() ?? '-',
                $conSesion ? 'Sí' : 'NO',
                $conPermiso ? 'Sí' : 'NO',
            ];
        }

        if ($filas === []) {
            $this->info('Todas las rutas de la aplicación exigen sesión y permiso.');

            return self::SUCCESS;
        }

        $this->table(['Método', 'URI', 'Nombre', 'Sesión', 'Permiso'], $filas);
        $this->warn($pendientes . ' ruta(s) sin sesión o sin permiso.');

        return self::SUCCESS;
    }

    private function esDeLaAplicacion(Route $ruta): bool
    {
        $accion = ltrim($ruta->getActionName(), '\\');

        if ($accion === 'Closure') {
            $archivo = (new ReflectionFunction($ruta->getAction('uses')))->getFileName();

            return str_starts_with(
                str_replace('\\', '/', (string) $archivo),
                str_replace('\\', '/', base_path('routes'))
            );
        }

        if (str_starts_with($accion, 'App\\Http\\Controllers\\Seguridad\\')) {
            return false;
        }

        foreach (config('seguridad.auditar_prefijos') as $prefijo) {
            if (str_starts_with($accion, $prefijo)) {
                return true;
            }
        }

        return false;
    }

    private function tiene(array $middleware, array $clases, array $aliasPrefixes = []): bool
    {
        foreach ($middleware as $item) {
            if (! is_string($item)) {
                continue;
            }

            if (in_array($item, $aliasPrefixes, true)) {
                return true;
            }

            foreach ($aliasPrefixes as $aliasPrefix) {
                if (str_ends_with($aliasPrefix, ':') && str_starts_with($item, $aliasPrefix)) {
                    return true;
                }
            }

            foreach ($clases as $clase) {
                if ($item === $clase || str_starts_with($item, $clase . ':')) {
                    return true;
                }
            }
        }

        return false;
    }
}
