<?php

namespace App\Http\Controllers;

use App\Services\precioServicioService;
use App\Services\servicioService;
use App\Services\tipoVehiculoService;
use App\Http\Requests\precioServicioStoreRequest;
use App\Http\Requests\precioServicioUpdateRequest;

class PrecioServicioController extends Controller 
{
    private precioServicioService $precioServicioService;
    private servicioService $servicioService;
    private tipoVehiculoService $tipoVehiculoService;

    public function __construct(precioServicioService $precioServicioService, servicioService $servicioService, tipoVehiculoService $tipoVehiculoService)
    {
        $this->precioServicioService = $precioServicioService;
        $this->servicioService = $servicioService;
        $this->tipoVehiculoService = $tipoVehiculoService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $precioServicio = $this->precioServicioService->listarTodo();
        return view('precioServicio.index', compact('precioServicio'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicios = $this->servicioService->listarActivosConPrecios();
        $tipoVehiculos = $this->tipoVehiculoService->listarTodo();
        return view('precioServicio.crear', compact('servicios', 'tipoVehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(precioServicioStoreRequest $request)
    {
        $datos = $request->validated();
        $this->precioServicioService->guardar($datos);
        return redirect()->route('precioServicio.index')->with('success','precio del servicio creado correctamente.');
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
        $precioServicio = $this->precioServicioService->buscarPorId($id);
        $servicios = $this->servicioService->listarActivosConPrecios();
        $tipoVehiculos = $this->tipoVehiculoService->listarTodo();
        return view('precioServicio.editar', compact('precioServicio', 'servicios', 'tipoVehiculos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(precioServicioUpdateRequest $request, int $id)
    {
        $this->precioServicioService->actualizar($id, $request->validated());
        return redirect()->route('precioServicio.index')->with('success','precio del servicio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->precioServicioService->eliminar($id);
        return redirect()->route('precioServicio.index')->with('success','precio del servicio eliminado correctamente.');
    }
}
