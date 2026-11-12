<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReporteDiarioRequest;
use App\Http\Requests\ReportePeriodoRequest;
use App\Services\ReporteService;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function __construct(private ReporteService $service)
    {
    }

    public function diario(ReporteDiarioRequest $reporteDiarioRequest): View
    {
        return view('reportes.diario', $this->service->diario(
            $reporteDiarioRequest->validated('fecha')
        ));
    }

    public function periodo(ReportePeriodoRequest $reportePeriodoRequest): View
    {
        $datos = $reportePeriodoRequest->validated();

        return view('reportes.periodo', $this->service->prepararPeriodo(
            $datos['desde'] ?? null,
            $datos['hasta'] ?? null
        ));
    }
}
