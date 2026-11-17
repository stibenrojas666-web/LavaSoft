<?php

namespace App\Http\Controllers;


use App\Services\TipoVehiculoService;
use App\Http\Requests\TipoVehiculoStoreRequest;
use App\Http\Requests\TipoVehiculoUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;

class TipoVehiculoController extends Controller
{
    private TipoVehiculoService $tipoVehiculoService;

    public function __construct(TipoVehiculoService $tipoVehiculoService)
    {
        $this->tipoVehiculoService = $tipoVehiculoService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('tipoVehiculo.index', $this->tipoVehiculoService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipoVehiculo.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoVehiculoStoreRequest $tipoVehiculoStoreRequest)
    {
        $this->tipoVehiculoService->guardar($tipoVehiculoStoreRequest->validated());
        return redirect()->route('tipoVehiculo.index')->with('success', 'tipoVehiculo creado correctamente.');
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
        $tipoVehiculo = $this->tipoVehiculoService->buscarPorId($id);
        return view('tipoVehiculo.editar',compact('tipoVehiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoVehiculoUpdateRequest $tipoVehiculoUpdateRequest, int $id)
    {
        $this->tipoVehiculoService->actualizar($id, $tipoVehiculoUpdateRequest->validated());
        return redirect()->route('tipoVehiculo.index')->with('success', 'tipoVehiculo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$this->tipoVehiculoService->eliminar($id)) {
            return redirect()->route('tipoVehiculo.index')->with('error', 'No se puede eliminar el tipo porque tiene vehículos o precios de servicio relacionados.');
        }
        return redirect()->route('tipoVehiculo.index')->with('success', 'tipoVehiculo eliminado Correctamente.');
    }
}
