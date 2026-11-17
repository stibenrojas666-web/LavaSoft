<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoStoreRequest;
use App\Http\Requests\VehiculoUpdateRequest;
use App\Http\Requests\VehiculoIndexRequest;
use App\Services\VehiculoService;

/**use App\Services\*/
class VehiculoController extends Controller
{
    public function __construct(private VehiculoService $vehiculoService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(VehiculoIndexRequest $vehiculoIndexRequest)
    {
        return view('vehiculos.index', $this->vehiculoService->prepararIndiceConOpciones($vehiculoIndexRequest->validated()));
    }

    public function cambiarEstado(int $id)
    {
        $activo = $this->vehiculoService->cambiarEstado($id);

        return redirect()->route('vehiculos.index')->with(
            'success',
            $activo ? 'Vehículo activado correctamente.' : 'Vehículo desactivado correctamente.'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehiculos.crear', $this->vehiculoService->prepararFormulario());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculoStoreRequest $vehiculoStoreRequest)
    {
        $this->vehiculoService->keep($vehiculoStoreRequest->validated());
        return redirect()->route('vehiculos.index')->with('success','Vehículo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return view('vehiculos.historial', $this->vehiculoService->historial($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return view('vehiculos.editar', $this->vehiculoService->prepararFormulario($id));

        }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id,VehiculoUpdateRequest $vehiculoUpdateRequest)
    {
        $this->vehiculoService->update($id, $vehiculoUpdateRequest->validated());
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado correctamente.');      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$this->vehiculoService->delete($id)) {
            return redirect()->route('vehiculos.index')->with('error', 'No se puede eliminar el vehículo porque tiene citas relacionadas.');
        }

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }
}

