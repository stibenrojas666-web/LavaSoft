<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaIndexRequest;
use App\Services\AgendaServicioService;

class AgendaServiciosController extends Controller
{
    private AgendaServicioService $agendaServicioService;
    public function __construct(AgendaServicioService $agendaServicioService)
    {
        $this->agendaServicioService = $agendaServicioService;
    }

    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('agendaServicios.index', $this->agendaServicioService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    public function show(int $id)
    {
        return view('agendaServicios.ver', [
            'agendaServicio' => $this->agendaServicioService->findById($id),
        ]);
    }
}
