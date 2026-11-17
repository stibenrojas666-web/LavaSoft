<?php

namespace App\Services;

use App\Repositories\Contracts\ReporteRepositoryInterface as ReporteRepository;
use Illuminate\Support\Carbon;

class ReporteService
{
    public function __construct(private ReporteRepository $repository) {}

    public function diario(?string $fecha = null): array
    {
        $fecha ??= Carbon::today()->toDateString();
        $citas = $this->repository->citasAtendidasEnFecha($fecha);
        $citas->each(function ($cita): void {
            $cita->setAttribute(
                'serviciosResumen',
                $cita->agendaServicios->pluck('servicio.nombre')->filter()->implode(', ') ?: 'Sin servicios'
            );
        });
        $servicios = $this->repository->totalesPorServicio($fecha, $fecha);
        $empleados = $this->repository->rendimientoPorEmpleadoEntre($fecha, $fecha);

        $totalRecaudado = $this->repository->totalRecaudadoEnFecha($fecha);
        $totalComisiones = $this->repository->totalComisionesEntre($fecha, $fecha);

        return [
            'fecha' => $fecha,
            'citas' => $citas,
            'cantidadCitas' => $citas->count(),

            'totalRecaudado' => $totalRecaudado,
            'totalComisiones' => $totalComisiones,
            'ingresoDespuesComisiones' => $totalRecaudado - $totalComisiones,

            'comisionesPorEmpleado' => $this->comisionesConPorcentaje($fecha, $fecha),
            'mejorEmpleado' => $empleados->first(),
            'empleados' => $empleados,
            'empleadosLabels' => $empleados->map(fn ($fila) => trim($fila->nombre.' '.$fila->apellido))->values(),
            'empleadosValues' => $empleados->map(fn ($fila) => (float) $fila->totalGenerado)->values(),

            'serviciosLabels' => $servicios->map(
                fn ($fila) => $fila->servicio?->nombre ?? 'Servicio eliminado'
            )->values(),

            'serviciosValues' => $servicios->pluck('cantidad')
                ->map(fn ($cantidad) => (int) $cantidad)
                ->values(),

            'servicios' => $servicios,
        ];
    }

    public function periodo(string $desde, string $hasta): array
    {
        $desdeFecha = Carbon::parse($desde)->startOfDay();
        $hastaFecha = Carbon::parse($hasta)->endOfDay();

        $servicios = $this->repository->totalesPorServicio($desde, $hasta);
        $empleados = $this->repository->rendimientoPorEmpleadoEntre($desde, $hasta);

        $totalRecaudado = $this->repository->totalRecaudadoEntre(
            $desdeFecha->toDateString(),
            $hastaFecha->toDateString()
        );

        $totalComisiones = $this->repository->totalComisionesEntre($desde, $hasta);

        return [
            'citasAtendidas' => $this->repository->citasAtendidasEntre($desde, $hasta),

            'totalRecaudado' => $totalRecaudado,
            'totalComisiones' => $totalComisiones,
            'ingresoDespuesComisiones' => $totalRecaudado - $totalComisiones,

            'comisionesPorEmpleado' => $this->comisionesConPorcentaje($desde, $hasta),
            'mejorEmpleado' => $empleados->first(),
            'empleados' => $empleados,
            'empleadosLabels' => $empleados->map(fn ($fila) => trim($fila->nombre.' '.$fila->apellido))->values(),
            'empleadosValues' => $empleados->map(fn ($fila) => (float) $fila->totalGenerado)->values(),

            'servicios' => $servicios,
            'totalServicios' => (float) $servicios->sum('total'),

            'serviciosLabels' => $servicios->map(
                fn ($fila) => $fila->servicio?->nombre ?? 'Servicio eliminado'
            )->values(),

            'serviciosValues' => $servicios->map(
                fn ($fila) => (float) $fila->total
            )->values(),
        ];
    }

    public function prepararPeriodo(?string $desde, ?string $hasta): array
    {
        $hoy = Carbon::today();
        $rangoSemana = [
            'desde' => $hoy->copy()->startOfWeek()->toDateString(),
            'hasta' => $hoy->copy()->endOfWeek()->toDateString(),
        ];
        $rangoMes = [
            'desde' => $hoy->copy()->startOfMonth()->toDateString(),
            'hasta' => $hoy->copy()->endOfMonth()->toDateString(),
        ];

        if ($desde === null && $hasta === null) {
            $desde = $rangoSemana['desde'];
            $hasta = $rangoSemana['hasta'];
        } else {
            $desde ??= $hasta;
            $hasta ??= $desde;
        }

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'rangoSemana' => $rangoSemana,
            'rangoMes' => $rangoMes,
            ...$this->periodo($desde, $hasta),
        ];
    }

    private function comisionesConPorcentaje(string $desde, string $hasta)
    {
        return $this->repository->comisionesPorEmpleadoEntre($desde, $hasta)
            ->each(function ($fila): void {
                $fila->setAttribute('porcentajeEfectivo', (float) $fila->totalPagado > 0
                    ? ((float) $fila->totalComision / (float) $fila->totalPagado) * 100
                    : 0);
            });
    }
}
