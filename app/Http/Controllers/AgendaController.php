<?php

namespace App\Http\Controllers;
use App\Http\Requests\agendaStoreRequest;
use App\Http\Requests\agendaUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\EmpleadosDisponiblesRequest;
use App\Services\AgendaService;
use App\Services\clienteService;
use App\Services\empleadoService;
use App\Services\vehiculoService;
use App\Services\servicioService;

class AgendaController extends Controller
{
    private AgendaService $agendaService;
    private clienteService $clienteService;
    private empleadoService $empleadoService;
    private vehiculoService $vehiculoService;
    private servicioService $servicioService;

    public function __construct(
        AgendaService $agendaService,
        clienteService $clienteService,
        empleadoService $empleadoService,
        vehiculoService $vehiculoService,
        servicioService $servicioService
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
    public function index(BusquedaIndexRequest $request)
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado', 'desde', 'hasta', 'orden']));
        $agenda = $this->agendaService->buscar($buscar, $filtros);
        $vista = $datos['vista'] ?? 'lista';
        $mes = $datos['mes'] ?? now()->format('Y-m');
        $inicioMes = \Illuminate\Support\Carbon::createFromFormat('!Y-m', $mes)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();
        $fechaCalendario = $datos['fecha'] ?? ($inicioMes->isCurrentMonth() ? today()->toDateString() : $inicioMes->toDateString());
        $citasCalendario = $vista === 'calendario'
            ? $this->agendaService->buscarParaCalendario(
                $buscar,
                array_intersect_key($datos, array_flip(['estado', 'desde', 'hasta'])),
                $inicioMes->toDateString(),
                $finMes->toDateString()
            )
            : collect();

        return view('Agenda.index', compact('agenda', 'buscar', 'filtros', 'mes', 'vista', 'citasCalendario', 'fechaCalendario'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cliente = $this->clienteService->listarTodo();
        $empleado = collect();
        $vehiculo = $this->vehiculoService->getAll();
        $serviciosPrecios = $this->servicioService->catalogoActivosConPrecios();

        return view('Agenda.crear', compact('cliente', 'empleado', 'vehiculo', 'serviciosPrecios'));
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
        $empleado = $agenda->empleado ? collect([$agenda->empleado]) : collect();
        $vehiculo = $this->vehiculoService->getAll();
        $serviciosPrecios = $this->servicioService->catalogoActivosConPrecios();

        return view('Agenda.editar', compact('agenda', 'cliente', 'empleado', 'vehiculo', 'serviciosPrecios'));
    }

    /** Retorna empleados con turno en ese día/hora y sin otra cita activa. */
    public function empleadosDisponibles(EmpleadosDisponiblesRequest $request)
    {
        $datos = $request->validated();

        $empleados = $this->empleadoService->disponiblesEnHorario(
            $datos['fecha'],
            $datos['hora'],
            isset($datos['agendaId']) ? (int) $datos['agendaId'] : null
        );

        return response()->json($empleados->map(fn ($persona) => [
            'id' => $persona->id,
            'nombre' => trim($persona->nombre . ' ' . $persona->apellido),
            'jornada' => $persona->turnos->pluck('jornada')->unique()->implode(', '),
        ]));
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
        if (!$this->agendaService->delete($id)) {
            return redirect()->route('agenda.index')->with('error', 'No se puede eliminar la cita porque tiene servicios o pagos relacionados.');
        }

        return redirect()->route('agenda.index')->with('success', 'Cita eliminada correctamente.');
    }
}
