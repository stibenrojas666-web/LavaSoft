<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoStoreRequest;
use App\Http\Requests\VehiculoUpdateRequest;
use App\Services\VehiculoService;
use App\Services\clienteService;
use App\Services\tipoVehiculoService;

/**use App\Services\*/
class VehiculoController extends Controller
{
private VehiculoService $VehiculoService;
private clienteService $clienteService;
private tipoVehiculoService $tipoVehiculoService;

    public function __construct(VehiculoService $VehiculoService, clienteService $clienteService,tipoVehiculoService $tipoVehiculoService)
    {
        $this->VehiculoService = $VehiculoService;
        $this->clienteService = $clienteService;
        $this->tipoVehiculoService = $tipoVehiculoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculo = $this->VehiculoService->getAll();

        return view('Vehiculos.index', compact('vehiculo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cliente = $this->clienteService->listarTodo();
        $tipoVehiculo = $this->tipoVehiculoService->listarTodo();

        return view('vehiculos.crear', compact('cliente','tipoVehiculo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculoStoreRequest $vehiculoStoreRequest)
    {
        $datos = $vehiculoStoreRequest->validated();
        $this->VehiculoService->keep($datos);
        return redirect()->route('vehiculos.index')->with('success','Vehículo creado correctamente.');
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
        $vehiculo = $this->VehiculoService->findById($id);
        $cliente = $this->clienteService->listarTodo();
        $tipoVehiculo = $this->tipoVehiculoService->listarTodo();
        return view('Vehiculos.editar',compact('vehiculo','cliente','tipoVehiculo'));

        }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id,VehiculoUpdateRequest $vehiculoUpdateRequest)
    {
        $datos = $vehiculoUpdateRequest->validated();
        $this->VehiculoService->update($id,$datos);
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado correctamente.');      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->VehiculoService->delete($id);

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }
}

