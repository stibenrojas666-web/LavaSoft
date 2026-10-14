<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaServicioStoreRequest;
use App\Http\Requests\AgendaServicioUpdateRequest;
use App\Models\agenda;
use App\Models\precioServicio;
use App\Services\AgendaService;
use App\Services\AgendaServicioService;
use App\Services\servicioService;
use Illuminate\Validation\ValidationException;

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
        $datos = $request->validated();
        $datos['Precio'] = $this->precioSegunVehiculo($datos['agendaId'], $datos['servicioId']);
        $this->agendaServicioService->keep($datos);

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
        $datos = $request->validated();
        $datos['Precio'] = $this->precioSegunVehiculo($datos['agendaId'], $datos['servicioId']);
        $this->agendaServicioService->update($id, $datos);

        return redirect()->route('agendaServicio.index')
            ->with('success', 'Servicio de la cita actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $this->agendaServicioService->delete($id);

        return redirect()->route('agendaServicio.index')
            ->with('success', 'Servicio quitado de la cita correctamente.');
    }

    /** Toma el precio vigente para el servicio y tipo de vehículo de la cita. */
    private function precioSegunVehiculo(int $agendaId, int $servicioId): string
    {
        $cita = agenda::with('vehiculo')->findOrFail($agendaId);
        $tipoVehiculoId = $cita->vehiculo?->tipoVehiculoId;

        $precio = precioServicio::query()
            ->where('servicio_id', $servicioId)
            ->where('tipo_vehiculo_id', $tipoVehiculoId)
            ->value('precio');

        if ($precio === null) {
            throw ValidationException::withMessages([
                'servicioId' => 'No hay un precio registrado para este servicio y tipo de vehículo.',
            ]);
        }

        return (string) $precio;
    }
}
