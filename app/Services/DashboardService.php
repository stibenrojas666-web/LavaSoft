<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function __construct(private DashboardRepository $dashboardRepository)
    {
    }

    public function resumen(): array
    {
        $hoy = Carbon::today();
        $inicio = $hoy->copy()->subDays(6);
        $dias = [];
        $ingresos = [];
        $citasPorDia = [];
        $ingresosPorDia = $this->dashboardRepository->ingresosEntre($inicio, $hoy)
            ->groupBy(fn ($pago) => Carbon::parse($pago->FechaPago)->toDateString());

        for ($fecha = $inicio->copy(); $fecha->lte($hoy); $fecha->addDay()) {
            $clave = $fecha->toDateString();
            $dias[] = $fecha->format('d/m');
            $ingresos[] = (float) ($ingresosPorDia->get($clave, collect())->sum('Monto'));
            $citasPorDia[] = $this->dashboardRepository->citasEnFecha($fecha);
        }

        $topServicios = $this->dashboardRepository->topServicios();

        return [
            'serviciosHoy' => $this->dashboardRepository->serviciosHoy($hoy),
            'cajaHoy' => $this->dashboardRepository->cajaHoy($hoy),
            'lavadoresActivos' => $this->dashboardRepository->lavadoresActivos(),
            'citasPendientes' => $this->dashboardRepository->citasPendientes(),
            'clientesTotal' => $this->dashboardRepository->totalClientes(),
            'vehiculosTotal' => $this->dashboardRepository->totalVehiculos(),
            'citasHoy' => $this->dashboardRepository->citasEnFecha($hoy),
            'dias' => $dias,
            'ingresos' => $ingresos,
            'citasPorDia' => $citasPorDia,
            'serviciosLabels' => $topServicios->map(fn ($servicio) => $servicio->servicio->nombre ?? 'N/A'),
            'serviciosValues' => $topServicios->pluck('total'),
            'ultimasCitas' => $this->dashboardRepository->ultimasCitas(),
        ];
    }
}
