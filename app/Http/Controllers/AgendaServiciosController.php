<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaServicioStoreRequest;
use App\Http\Requests\AgendaServicioUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Services\AgendaService;
use App\Services\AgendaServicioService;
use App\Services\servicioService;
use App\Services\vehiculoService;

class AgendaServiciosController extends Controller
{
    private AgendaServicioService $agendaServicioService;
    private AgendaService $agendaService;
    private servicioService $servicioService;
    private vehiculoService $vehiculoService;

    public function __construct(
        AgendaServicioService $agendaServicioService,
        AgendaService $agendaService,
        servicioService $servicioService,
        vehiculoService $vehiculoService
    ) {
        $this->agendaServicioService = $agendaServicioService;
        $this->agendaService = $agendaService;
        $this->servicioService = $servicioService;
        $this->vehiculoService = $vehiculoService;
    }

    public function index(BusquedaIndexRequest $request)
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado', 'desde', 'hasta']));
        $agendaServicio = $this->agendaServicioService->buscar($buscar, $filtros);

        return view('agendaServicios.index', compact('agendaServicio', 'buscar', 'filtros'));
    }

    public function create()
    {
        $agenda = $this->agendaService->getAll();
        $servicio = $this->servicioService->listarTodo();
        $vehiculo = $this->vehiculoService->getAll();

        return view('agendaServicios.crear', compact('agenda', 'servicio', 'vehiculo'));
    }

    public function store(AgendaServicioStoreRequest $request)
    {
        $this->agendaServicioService->keep($request->validated());

        return redirect()->route('agendaServicio.index')
            ->with('success', 'Servicio agregado a la cita correctamente.');
    }

    public function show()
    {
        //
    }

    public function edit(int $id)
    {
        $agendaServicio = $this->agendaServicioService->findById($id);
        $agenda = $this->agendaService->getAll();
        $servicio = $this->servicioService->listarTodo();
        $vehiculo = $this->vehiculoService->getAll();

        return view('agendaServicios.editar', compact('agendaServicio', 'agenda', 'servicio', 'vehiculo'));
    }

    public function update(int $id, AgendaServicioUpdateRequest $request)
    {
        $this->agendaServicioService->update($id, $request->validated());

        return redirect()->route('agendaServicio.index')
            ->with('success', 'Servicio de la cita actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $this->agendaServicioService->delete($id);

        return redirect()->route('agendaServicio.index')
            ->with('success', 'Servicio quitado de la cita correctamente.');
    }
}
