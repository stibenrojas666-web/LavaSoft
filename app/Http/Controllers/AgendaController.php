<?php

namespace App\Http\Controllers;

use App\Http\Requests\agendaStoreRequest;
use App\Http\Requests\agendaUpdateRequest;
use App\Services\AgendaService;
use App\Services\clienteService;
use App\Services\empleadoService;
use App\Services\VehiculoService;

class AgendaController extends Controller
{
    private AgendaService $agendaService;
    private clienteService $clienteService;
    private empleadoService $empleadoService;
    private VehiculoService $vehiculoService;

    public function __construct(
        AgendaService $agendaService,
        clienteService $clienteService,
        empleadoService $empleadoService,
        VehiculoService $vehiculoService
    ) {
        $this->agendaService = $agendaService;
        $this->clienteService = $clienteService;
        $this->empleadoService = $empleadoService;
        $this->vehiculoService = $vehiculoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agenda = $this->agendaService->getAll();

        return view('agenda.index', compact('agenda'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cliente = $this->clienteService->listarTodo();
        $empleado = $this->empleadoService->listarTodo();
        $vehiculo = $this->vehiculoService->getAll();

        return view('agenda.crear', compact('cliente', 'empleado', 'vehiculo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(agendaStoreRequest $agendaStoreRequest)
    {
        $datos = $agendaStoreRequest->validated();
        $this->agendaService->keep($datos);

        return redirect()->route('agenda.index')->with('success', 'Cita agendada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $agenda = $this->agendaService->findById($id);
        $cliente = $this->clienteService->listarTodo();
        $empleado = $this->empleadoService->listarTodo();
        $vehiculo = $this->vehiculoService->getAll();

        return view('agenda.editar', compact('agenda', 'cliente', 'empleado', 'vehiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, agendaUpdateRequest $agendaUpdateRequest)
    {
        $datos = $agendaUpdateRequest->validated();
        $this->agendaService->update($id, $datos);

        return redirect()->route('agenda.index')->with('success', 'Cita actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->agendaService->delete($id);

        return redirect()->route('agenda.index')->with('success', 'Cita eliminada correctamente.');
    }
}