<?php

namespace App\Providers;

use App\Http\Middleware\Seguridad\AutorizarModulo;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SeguridadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function ($usuario, string $habilidad): ?bool {
            if (! method_exists($usuario, 'hasRole')) {
                return null;
            }

            return $usuario->hasRole(config('seguridad.rol_admin')) ? true : null;
        });

        $this->app['router']->aliasMiddleware('modulo', AutorizarModulo::class);

        $this->loadRoutesFrom(base_path('routes/seguridad.php'));
    }
}
