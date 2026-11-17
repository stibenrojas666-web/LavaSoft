<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoIndexRequest;
use App\Http\Requests\VehiculoStoreRequest;
use App\Http\Requests\VehiculoUpdateRequest;
use App\Services\VehiculoService;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function __construct(private VehiculoService $vehiculoService) {}

    public function index(VehiculoIndexRequest $request)
    {
        return view('vehiculos.index', $this->vehiculoService->prepararIndiceConOpciones($request->validated()));
    }

    public function cambiarEstado(Request $request, int $id)
    {
        $activo = $this->vehiculoService->cambiarEstado($id);

        return redirect()->to(route('vehiculos.index', $request->query()).'#vehiculo-'.$id)->with(
            'success',
            $activo ? 'Vehículo activado correctamente.' : 'Vehículo desactivado correctamente.'
        );
    }

    public function create(Request $request)
    {
        $datos = $this->vehiculoService->prepararFormulario();
        $datos['clienteSeleccionadoId'] = $request->query('clienteId');

        return view('vehiculos.crear', $datos);
    }

    public function store(VehiculoStoreRequest $request)
    {
        $vehiculo = $this->vehiculoService->keep($request->validated());

        if ($request->boolean('continuarAgenda') && strtolower((string) $vehiculo->estado) === 'activo') {
            return redirect()->route('agenda.create', [
                'clienteId' => $vehiculo->clienteID,
                'vehiculoId' => $vehiculo->id,
            ])->with('success', 'Vehículo creado correctamente. Ahora completa los datos de la cita.');
        }

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado correctamente.');
    }

    public function show(int $id)
    {
        return view('vehiculos.historial', $this->vehiculoService->historial($id));
    }

    public function edit(int $id, Request $request)
    {
        $datos = $this->vehiculoService->prepararFormulario($id);
        $datos['continuarAgenda'] = $request->boolean('continuarAgenda')
            && strtolower((string) $datos['vehiculo']->estado) === 'activo';

        return view('vehiculos.editar', $datos);
    }

    public function update(int $id, VehiculoUpdateRequest $request)
    {
        $vehiculo = $this->vehiculoService->update($id, $request->validated());

        if ($request->boolean('continuarAgenda') && strtolower((string) $vehiculo->estado) === 'activo') {
            return redirect()->route('agenda.create', [
                'clienteId' => $vehiculo->clienteID,
                'vehiculoId' => $vehiculo->id,
            ])->with('success', 'Vehículo actualizado. Continúa completando la cita.');
        }

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        if (! $this->vehiculoService->delete($id)) {
            return redirect()->route('vehiculos.index')->with('error', 'No se puede eliminar el vehículo porque tiene citas relacionadas.');
        }

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado correctamente.');
    }
}
