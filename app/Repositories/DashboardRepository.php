<?php

namespace App\Repositories;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\clientes;
use App\Models\empleado;
use App\Models\pago;
use App\Models\vehiculo;
use Illuminate\Support\Carbon;

class DashboardRepository
{
    public function serviciosHoy(Carbon $hoy): int
    {
        return agendaServicios::whereHas('agenda', fn ($query) => $query->whereDate('fecha', $hoy))->count();
    }

    public function cajaHoy(Carbon $hoy): float
    {
        return (float) pago::whereDate('FechaPago', $hoy)->sum('Monto');
    }

    public function lavadoresActivos(): int
    {
        return empleado::where('estado', true)->count();
    }

    public function citasPendientes(): int
    {
        return agenda::where('estado', 'pendiente')->count();
    }

    public function totalClientes(): int
    {
        return clientes::count();
    }

    public function totalVehiculos(): int
    {
        return vehiculo::count();
    }

    public function citasEnFecha(Carbon $fecha): int
    {
        return agenda::whereDate('fecha', $fecha)->count();
    }

    public function ingresosEntre(Carbon $inicio, Carbon $fin)
    {
        return pago::whereBetween('FechaPago', [$inicio->copy()->startOfDay(), $fin->copy()->endOfDay()])
            ->get(['FechaPago', 'Monto']);
    }

    public function topServicios()
    {
        return agendaServicios::selectRaw('servicioId, count(*) as total')
            ->with('servicio')
            ->groupBy('servicioId')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }

    public function ultimasCitas()
    {
        return agenda::with(['cliente', 'vehiculo'])->latest('created_at')->take(6)->get();
    }
}
