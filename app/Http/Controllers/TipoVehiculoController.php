<?php

namespace App\Http\Controllers;


use App\Models\tipoVehiculo;
use Illuminate\Http\Request;
use App\Services\tipoVehiculoService;
use App\Http\Requests\tipoVehiculoStoreRequest;
use App\Http\Requests\tipoVehiculoUpdateRequest;

class TipoVehiculoController extends Controller
{
    private tipoVehiculoService $tipoVehiculoService;

    public function __construct(tipoVehiculoService $tipoVehiculoService)
    {
        $this->tipoVehiculoService = $tipoVehiculoService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoVehiculos = $this->tipoVehiculoService->listarTodo();
        return view('tipoVehiculo.index', compact('tipoVehiculos'));
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
    public function store(tipoVehiculoStoreRequest $request)
    {
        $datos = $request->validated();
        $this->tipoVehiculoService->guardar($datos);
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
    public function update(tipoVehiculoUpdateRequest $request, int $id)
    {
        $this->tipoVehiculoService->actualizar($id, $request->validated());
        return redirect()->route('tipoVehiculo.index')->with('success', 'tipoVehiculo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->tipoVehiculoService->eliminar($id);
        return redirect()->route('tipoVehiculo.index')->with('success', 'tipoVehiculo eliminado Correctamente.');
    }
}
