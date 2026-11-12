<?php

namespace App\Repositories;

use App\Repositories\Contracts\DashboardRepositoryInterface;

use App\Models\Agenda;
use App\Models\AgendaServicios;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Pago;
use App\Models\Vehiculo;
use Illuminate\Support\Carbon;

class DashboardRepository implements DashboardRepositoryInterface {
    public function serviciosHoy(Carbon $hoy): int
    {
         return AgendaServicios::whereHas('agenda', fn ($query) => $query
        ->whereDate('fecha', $hoy)
        ->where('estado', '!=', 'cancelada'))->count();
    }

    public function cajaHoy(Carbon $hoy): float
    {
        return (float) Pago::whereDate('FechaPago', $hoy)->sum('Monto');
    }

    public function lavadoresActivos(): int
    {
        return Empleado::where('estado', true)->count();
    }

    public function citasPendientes(): int
    {
        return Agenda::where('estado', 'pendiente')->count();
    }

    public function totalClientes(): int
    {
        return Cliente::count();
    }

    public function totalVehiculos(): int
    {
        return Vehiculo::count();
    }

    public function citasEnFecha(Carbon $fecha): int
    {
        return Agenda::whereDate('fecha', $fecha)->where('estado', '!=', 'cancelada')->count();
    }

    public function ingresosEntre(Carbon $inicio, Carbon $fin)
    {
        return Pago::whereBetween('FechaPago', [$inicio->copy()->startOfDay(), $fin->copy()->endOfDay()])
            ->get(['FechaPago', 'Monto']);
    }

    public function topServicios()
    {
        return AgendaServicios::selectRaw('servicioId, count(*) as total')
            ->with('servicio')
            ->whereHas('agenda', fn ($q) => $q->where('estado', 'terminada'))
            ->groupBy('servicioId')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }

    public function ultimasCitas()
    {
        return Agenda::with(['cliente', 'empleado', 'agendaServicios.vehiculo.tipoVehiculo', 'agendaServicios.servicio'])
            ->withSum('agendaServicios', 'Precio')
            ->latest('created_at')
            ->take(6)
            ->get();
    }
}
