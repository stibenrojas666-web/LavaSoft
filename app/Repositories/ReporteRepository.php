<?php

namespace App\Repositories;

use App\Repositories\Contracts\ReporteRepositoryInterface;

use App\Models\Agenda;
use App\Models\AgendaServicios;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Collection;

class ReporteRepository implements ReporteRepositoryInterface {
    public function citasAtendidasEnFecha(string $fecha): Collection
    {
        return Agenda::query()
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
        return (float) Pago::query()->whereDate('FechaPago', $fecha)->sum('Monto');
    }

    public function citasAtendidasEntre(string $desde, string $hasta): int
    {
        return Agenda::query()
            ->where('estado', 'terminada')
            ->whereBetween('fecha', [$desde, $hasta])
            ->count();
    }

    public function totalRecaudadoEntre(string $desde, string $hasta): float
    {
        return (float) Pago::query()
            ->whereBetween('FechaPago', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->sum('Monto');
    }

    public function totalesPorServicio(string $desde, string $hasta): Collection
    {
        return AgendaServicios::query()
            ->selectRaw('servicioId, COUNT(*) as cantidad, SUM(Precio) as total')
            ->whereHas('agenda', fn ($query) => $query
                ->where('estado', 'terminada')
                ->whereBetween('fecha', [$desde, $hasta]))
            ->with('servicio:id,nombre')
            ->groupBy('servicioId')
            ->orderByDesc('total')
            ->get();
    }
    public function comisionesPorEmpleadoEntre(string $desde, string $hasta): Collection
    {
        return Pago::query()
            ->selectRaw('empleadoId, SUM(valorComision) as totalComision, COUNT(*) as cantidadPagos')
            ->whereBetween('FechaPago', [
                $desde . ' 00:00:00',
                $hasta . ' 23:59:59',
            ])
            ->whereNotNull('empleadoId')
            ->with('empleado:id,nombre,apellido')
            ->groupBy('empleadoId')
            ->orderByDesc('totalComision')
            ->get();
    }
    public function totalComisionesEntre(string $desde, string $hasta): float
    {
        return (float) Pago::query()
            ->whereBetween('FechaPago', [
                $desde . ' 00:00:00',
                $hasta . ' 23:59:59',
            ])
            ->sum('valorComision');
    }

    public function mejorEmpleadoEntre(string $desde, string $hasta)
    {
        return AgendaServicios::query()
            ->join('agendas', 'agenda_servicios.agendaId', '=', 'agendas.id')
            ->join('empleados', 'agendas.empleadoId', '=', 'empleados.id')
            ->selectRaw('
                empleados.id as empleadoId,
                empleados.nombre,
                empleados.apellido,
                COUNT(*) as cantidadServicios,
                SUM(agenda_servicios.Precio) as totalGenerado
            ')
            ->where('agendas.estado', 'terminada')
            ->whereBetween('agendas.fecha', [$desde, $hasta])
            ->groupBy('empleados.id', 'empleados.nombre', 'empleados.apellido')
            ->orderByDesc('totalGenerado')
            ->orderByDesc('cantidadServicios')
            ->first();
    }

    
}
