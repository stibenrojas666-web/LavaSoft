<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReporteDiarioRequest;
use App\Http\Requests\ReportePeriodoRequest;
use App\Services\ReporteService;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function __construct(private ReporteService $service)
    {
    }

    public function diario(ReporteDiarioRequest $request): View
    {
        $fecha = $request->validated('fecha') ?: Carbon::today()->toDateString();

        return view('reportes.diario', [
            'fecha' => $fecha,
            ...$this->service->diario($fecha),
        ]);
    }

    public function periodo(ReportePeriodoRequest $request): View
    {
        $datos = $request->validated();
        $hoy = Carbon::today();
        if (!isset($datos['desde']) && !isset($datos['hasta'])) {
            $desde = $hoy->copy()->startOfWeek()->toDateString();
            $hasta = $hoy->copy()->endOfWeek()->toDateString();
        } else {
            $desde = $datos['desde'] ?? $datos['hasta'];
            $hasta = $datos['hasta'] ?? $datos['desde'];
        }

        return view('reportes.periodo', [
            'desde' => $desde,
            'hasta' => $hasta,
            'rangoSemana' => [
                'desde' => $hoy->copy()->startOfWeek()->toDateString(),
                'hasta' => $hoy->copy()->endOfWeek()->toDateString(),
            ],
            'rangoMes' => [
                'desde' => $hoy->copy()->startOfMonth()->toDateString(),
                'hasta' => $hoy->copy()->endOfMonth()->toDateString(),
            ],
            ...$this->service->periodo($desde, $hasta),
        ]);
    }
}
