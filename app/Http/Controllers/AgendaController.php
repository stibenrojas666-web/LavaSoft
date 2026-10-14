<?php

namespace App\Http\Controllers;
use App\Http\Requests\agendaStoreRequest;
use App\Http\Requests\agendaUpdateRequest;
use App\Models\empleado;
use App\Services\AgendaService;
use App\Services\clienteService;
use App\Services\empleadoService;
use App\Services\VehiculoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $empleado = collect();
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
        $empleado = $agenda->empleado ? collect([$agenda->empleado]) : collect();
        $vehiculo = $this->vehiculoService->getAll();

        return view('agenda.editar', compact('agenda', 'cliente', 'empleado', 'vehiculo'));
    }

    /** Retorna empleados con turno en ese día/hora y sin otra cita activa. */
    public function empleadosDisponibles(Request $request)
    {
        $datos = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'agendaId' => 'nullable|integer|exists:agendas,id',
        ]);

        $dias = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'];
        $dia = $dias[Carbon::parse($datos['fecha'])->dayOfWeekIso];
        $hora = $datos['hora'] . ':00';

        $empleados = empleado::query()
            ->where('estado', true)
            ->whereHas('turnos', function ($query) use ($dia, $hora) {
                $query->where('dia', $dia)
                    ->where('horaInicio', '<=', $hora)
                    ->where('horaFin', '>', $hora);
            })
            ->whereDoesntHave('agendas', function ($query) use ($datos) {
                $query->where('fecha', $datos['fecha'])
                    ->whereTime('hora', '=', $datos['hora'])
                    ->where('estado', '!=', 'cancelada')
                    ->when($datos['agendaId'] ?? null, fn ($q, $id) => $q->where('id', '!=', $id));
            })
            ->with(['turnos' => fn ($query) => $query->where('dia', $dia)
                ->where('horaInicio', '<=', $hora)->where('horaFin', '>', $hora)])
            ->orderBy('nombre')
            ->get();

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
        $this->agendaService->delete($id);

        return redirect()->route('agenda.index')->with('success', 'Cita eliminada correctamente.');
    }
}
