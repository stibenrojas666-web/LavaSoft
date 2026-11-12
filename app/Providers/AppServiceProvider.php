<?php

namespace App\Providers;

use App\Services\ConfiguracionLavaderoService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'layouts.partials.sidebar',
            'pagos.factura',
            'pagos.partials.detalleFactura',
            'emails.factura-cita',
            'emails.factura-pdf',
        ], function ($view): void {
            $view->with('configuracionLavadero', app(ConfiguracionLavaderoService::class)->obtener());
        });
    }
}
