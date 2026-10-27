<?php

namespace App\Services;

use App\Repositories\ReporteRepository;
use Illuminate\Support\Carbon;

class ReporteService
{
    public function __construct(private ReporteRepository $repository)
    {
    }

    public function diario(string $fecha): array
    {
        $citas = $this->repository->citasAtendidasEnFecha($fecha);
        $servicios = $this->repository->totalesPorServicio($fecha, $fecha);

        return [
            'citas' => $citas,
            'cantidadCitas' => $citas->count(),
            'totalRecaudado' => $this->repository->totalRecaudadoEnFecha($fecha),
            'serviciosLabels' => $servicios->map(fn ($fila) => $fila->servicio?->nombre ?? 'Servicio eliminado')->values(),
            'serviciosValues' => $servicios->pluck('cantidad')->map(fn ($cantidad) => (int) $cantidad)->values(),
            'servicios' => $servicios,
        ];
    }

    public function periodo(string $desde, string $hasta): array
    {
        $desdeFecha = Carbon::parse($desde)->startOfDay();
        $hastaFecha = Carbon::parse($hasta)->endOfDay();
        $servicios = $this->repository->totalesPorServicio($desde, $hasta);

        return [
            'citasAtendidas' => $this->repository->citasAtendidasEntre($desde, $hasta),
            'totalRecaudado' => $this->repository->totalRecaudadoEntre(
                $desdeFecha->toDateString(),
                $hastaFecha->toDateString()
            ),
            'servicios' => $servicios,
            'totalServicios' => (float) $servicios->sum('total'),
            'serviciosLabels' => $servicios->map(fn ($fila) => $fila->servicio?->nombre ?? 'Servicio eliminado')->values(),
            'serviciosValues' => $servicios->map(fn ($fila) => (float) $fila->total)->values(),
        ];
    }
}
