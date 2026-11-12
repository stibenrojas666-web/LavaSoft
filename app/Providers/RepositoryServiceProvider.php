<?php

namespace App\Providers;

use App\Repositories\AgendaRepository;
use App\Repositories\AgendaServicioRepository;
use App\Repositories\ConfiguracionLavaderoRepository;
use App\Repositories\Contracts\AgendaRepositoryInterface;
use App\Repositories\Contracts\AgendaServicioRepositoryInterface;
use App\Repositories\Contracts\ClienteRepositoryInterface;
use App\Repositories\Contracts\ConfiguracionLavaderoRepositoryInterface;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Repositories\Contracts\EmpleadoRepositoryInterface;
use App\Repositories\Contracts\PagoRepositoryInterface;
use App\Repositories\Contracts\PrecioServicioRepositoryInterface;
use App\Repositories\Contracts\ReporteRepositoryInterface;
use App\Repositories\Contracts\ServicioRepositoryInterface;
use App\Repositories\Contracts\TipoVehiculoRepositoryInterface;
use App\Repositories\Contracts\TurnoRepositoryInterface;
use App\Repositories\Contracts\VehiculoRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\PagoRepository;
use App\Repositories\ReporteRepository;
use App\Repositories\Seguridad\Contracts\PermisoRepositoryInterface;
use App\Repositories\Seguridad\Contracts\RolRepositoryInterface;
use App\Repositories\Seguridad\Contracts\UsuarioRepositoryInterface;
use App\Repositories\Seguridad\Contracts\TokenRecuperacionRepositoryInterface;
use App\Repositories\Seguridad\PermisoRepository;
use App\Repositories\Seguridad\RolRepository;
use App\Repositories\Seguridad\UsuarioRepository;
use App\Repositories\Seguridad\TokenRecuperacionRepository;
use App\Repositories\VehiculoRespository;
use App\Repositories\ClienteRepository;
use App\Repositories\EmpleadoRepository;
use App\Repositories\PrecioServicioRepository;
use App\Repositories\ServicioRepository;
use App\Repositories\TipoVehiculoRepository;
use App\Repositories\TurnoRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AgendaRepositoryInterface::class, AgendaRepository::class);
        $this->app->bind(AgendaServicioRepositoryInterface::class, AgendaServicioRepository::class);
        $this->app->bind(ClienteRepositoryInterface::class, ClienteRepository::class);
        $this->app->bind(ConfiguracionLavaderoRepositoryInterface::class, ConfiguracionLavaderoRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(EmpleadoRepositoryInterface::class, EmpleadoRepository::class);
        $this->app->bind(PagoRepositoryInterface::class, PagoRepository::class);
        $this->app->bind(PermisoRepositoryInterface::class, PermisoRepository::class);
        $this->app->bind(PrecioServicioRepositoryInterface::class, PrecioServicioRepository::class);
        $this->app->bind(ReporteRepositoryInterface::class, ReporteRepository::class);
        $this->app->bind(RolRepositoryInterface::class, RolRepository::class);
        $this->app->bind(ServicioRepositoryInterface::class, ServicioRepository::class);
        $this->app->bind(TipoVehiculoRepositoryInterface::class, TipoVehiculoRepository::class);
        $this->app->bind(TurnoRepositoryInterface::class, TurnoRepository::class);
        $this->app->bind(UsuarioRepositoryInterface::class, UsuarioRepository::class);
        $this->app->bind(TokenRecuperacionRepositoryInterface::class, TokenRecuperacionRepository::class);
        $this->app->bind(VehiculoRepositoryInterface::class, VehiculoRespository::class);
    }
}
