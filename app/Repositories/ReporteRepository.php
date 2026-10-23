<?php

namespace App\Repositories;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\pago;
use Illuminate\Database\Eloquent\Collection;

class ReporteRepository
{
    public function citasAtendidasEnFecha(string $fecha): Collection
    {
        return agenda::query()
            ->select(['id', 'clienteId', 'empleadoId', 'fecha', 'hora', 'estado'])
            ->whereDate('fecha', $fecha)
            ->where('estado', 'terminada')
            ->with([
                'cliente:id,nombreCliente,apellidoCliente',
                'empleado:id,nombre,apellido',
                'agendaServicios:id,agendaId,vehiculoId,servicioId,Precio',
                'agendaServicios.servicio:id,nombre',
            ])
            ->withSum('agendaServicios', 'Precio')
            ->orderBy('hora')
            ->get();
    }

    public function totalRecaudadoEnFecha(string $fecha): float
    {
        return (float) pago::query()->whereDate('FechaPago', $fecha)->sum('Monto');
    }

    public function citasAtendidasEntre(string $desde, string $hasta): int
    {
        return agenda::query()
            ->where('estado', 'terminada')
            ->whereBetween('fecha', [$desde, $hasta])
            ->count();
    }

    public function totalRecaudadoEntre(string $desde, string $hasta): float
    {
        return (float) pago::query()
            ->whereBetween('FechaPago', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->sum('Monto');
    }

    public function totalesPorServicio(string $desde, string $hasta): Collection
    {
        return agendaServicios::query()
            ->selectRaw('servicioId, COUNT(*) as cantidad, SUM(Precio) as total')
            ->whereHas('agenda', fn ($query) => $query
                ->where('estado', 'terminada')
                ->whereBetween('fecha', [$desde, $hasta]))
            ->with('servicio:id,nombre')
            ->groupBy('servicioId')
            ->orderByDesc('total')
            ->get();
    }
}
