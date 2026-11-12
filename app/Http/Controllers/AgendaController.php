<?php

namespace App\Http\Controllers;
use App\Http\Requests\AgendaStoreRequest;
use App\Http\Requests\AgendaUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\EmpleadosDisponiblesRequest;
use App\Services\AgendaService;
use App\Services\ClienteService;
use App\Services\EmpleadoService;
use App\Services\VehiculoService;
use App\Services\ServicioService;

class AgendaController extends Controller
{
    private AgendaService $agendaService;
    private ClienteService $clienteService;
    private EmpleadoService $empleadoService;
    private VehiculoService $vehiculoService;
    private ServicioService $servicioService;

    public function __construct(
        AgendaService $agendaService,
        ClienteService $clienteService,
        EmpleadoService $empleadoService,
        VehiculoService $vehiculoService,
        ServicioService $servicioService
    ) {
        $this->agendaService = $agendaService;
        $this->clienteService = $clienteService;
        $this->empleadoService = $empleadoService;
        $this->vehiculoService = $vehiculoService;
        $this->servicioService = $servicioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaAgendaRequest)
    {
        return view('Agenda.index', $this->agendaService->prepararIndice($busquedaAgendaRequest->validated()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cliente = $this->clienteService->listarTodo();
        $empleado = collect();
        $vehiculo = $this->vehiculoService->listarParaAgenda();
        $serviciosPrecios = $this->servicioService->catalogoActivosConPrecios();

        $bloquesIniciales = $this->agendaService->bloquesIniciales(null, old('vehiculos'), $serviciosPrecios);

        return view('Agenda.crear', compact('cliente', 'empleado', 'vehiculo', 'serviciosPrecios', 'bloquesIniciales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgendaStoreRequest $agendaStoreRequest)
    {
        $this->agendaService->keep($agendaStoreRequest->validated());

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
        if (! $this->agendaService->puedeEditar($agenda)) {
           return redirect()->route('agenda.index')
          ->with('error', 'Una cita terminada ya no se puede editar.');
          
}
        $cliente = $this->clienteService->listarTodo();
        $empleado = $agenda->empleado ? collect([$agenda->empleado]) : collect();
        $propios = $this->agendaService->vehiculosDeAgenda($agenda);
        $vehiculo = $this->vehiculoService->listarParaAgenda($propios);
        $serviciosPrecios = $this->servicioService->catalogoActivosConPrecios();

        $bloquesIniciales = $this->agendaService->bloquesIniciales($agenda, old('vehiculos'), $serviciosPrecios);

        return view('Agenda.editar', compact('agenda', 'cliente', 'empleado', 'vehiculo', 'serviciosPrecios', 'bloquesIniciales'));
    }

    /** Retorna empleados con turno en ese día/hora y sin otra cita activa. */
    /** Retorna empleados con turno completo y sin cruces de citas. */
public function empleadosDisponibles(EmpleadosDisponiblesRequest $empleadosDisponiblesRequest)
{
    return response()->json($this->empleadoService->disponiblesParaAgenda(
        $empleadosDisponiblesRequest->validated()
    ));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, AgendaUpdateRequest $agendaUpdateRequest)
    {
        $this->agendaService->update($id, $agendaUpdateRequest->validated());

        return redirect()->route('agenda.index')->with('success', 'Cita actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$this->agendaService->delete($id)) {
            return redirect()->route('agenda.index')->with('error', 'No se puede eliminar la cita porque ya tiene un pago registrado.');
        }

        return redirect()->route('agenda.index')->with('success', 'Cita eliminada correctamente.');
    }
}
