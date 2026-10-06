<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaServicioStoreRequest;
use App\Http\Requests\AgendaServicioUpdateRequest;
use App\Services\AgendaService;
use App\Services\AgendaServicioService;
use App\Services\servicioService;

class AgendaServiciosController extends Controller
{
    private AgendaServicioService $agendaServicioService;
    private AgendaService $agendaService;
    private servicioService $servicioService;

    public function __construct(
        AgendaServicioService $agendaServicioService,
        AgendaService $agendaService,
        servicioService $servicioService
    ) {
        $this->agendaServicioService = $agendaServicioService;
        $this->agendaService = $agendaService;
        $this->servicioService = $servicioService;
    }

    public function index()
    {
        $agendaServicio = $this->agendaServicioService->getAll();

        return view('agendaServicios.index', compact('agendaServicio'));
    }

    public function create()
    {
        $agenda = $this->agendaService->getAll();
        $servicio = $this->servicioService->listarTodo();

        return view('agendaServicios.crear', compact('agenda', 'servicio'));
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

        return view('agendaServicios.editar', compact('agendaServicio', 'agenda', 'servicio'));
    }

    public function update(int $id, AgendaServicioUpdateRequest $request)
    {
        $this->agendaServicioService->update($id, $request->validated());

        return redirect()->route('agendaServicios.index')
            ->with('success', 'Servicio de la cita actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $this->agendaServicioService->delete($id);

        return redirect()->route('agendaServicios.index')
            ->with('success', 'Servicio quitado de la cita correctamente.');
    }
}