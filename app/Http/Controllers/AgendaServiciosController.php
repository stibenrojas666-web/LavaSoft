<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaServicioStoreRequest;
use App\Http\Requests\AgendaServicioUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Services\AgendaService;
use App\Services\AgendaServicioService;
use App\Services\ServicioService;
use App\Services\VehiculoService;

class AgendaServiciosController extends Controller
{
    private AgendaServicioService $agendaServicioService;
    private AgendaService $agendaService;
    private ServicioService $servicioService;
    private VehiculoService $vehiculoService;

    public function __construct(
        AgendaServicioService $agendaServicioService,
        AgendaService $agendaService,
        ServicioService $servicioService,
        VehiculoService $vehiculoService
    ) {
        $this->agendaServicioService = $agendaServicioService;
        $this->agendaService = $agendaService;
        $this->servicioService = $servicioService;
        $this->vehiculoService = $vehiculoService;
    }

    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('agendaServicios.index', $this->agendaServicioService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    public function create()
    {
        $agenda = $this->agendaService->getAll();
        $servicio = $this->servicioService->listarTodo();
        $vehiculo = $this->vehiculoService->getAll();

        return view('agendaServicios.crear', compact('agenda', 'servicio', 'vehiculo'));
    }

    public function store(AgendaServicioStoreRequest $agendaServicioStoreRequest)
    {
        $this->agendaServicioService->keep($agendaServicioStoreRequest->validated());

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

    public function update(int $id, AgendaServicioUpdateRequest $agendaServicioUpdateRequest)
    {
        $this->agendaServicioService->update($id, $agendaServicioUpdateRequest->validated());

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
