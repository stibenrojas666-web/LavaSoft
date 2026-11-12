<?php

namespace App\Http\Controllers;

use App\Services\PrecioServicioService;
use App\Services\ServicioService;
use App\Services\TipoVehiculoService;
use App\Http\Requests\PrecioServicioStoreRequest;
use App\Http\Requests\PrecioServicioUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;

class PrecioServicioController extends Controller 
{
    private PrecioServicioService $precioServicioService;
    private ServicioService $servicioService;
    private TipoVehiculoService $tipoVehiculoService;

    public function __construct(PrecioServicioService $precioServicioService, ServicioService $servicioService, TipoVehiculoService $tipoVehiculoService)
    {
        $this->precioServicioService = $precioServicioService;
        $this->servicioService = $servicioService;
        $this->tipoVehiculoService = $tipoVehiculoService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        $datosListado = $this->precioServicioService->prepararListado(
            $busquedaIndexRequest->validated()
        );
        $tipoVehiculosOpciones = $this->tipoVehiculoService->opcionesParaFiltro();
        return view('precioServicio.index', [...$datosListado, 'tipoVehiculosOpciones' => $tipoVehiculosOpciones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicios = $this->servicioService->listarActivosConPrecios();
        $tipoVehiculos = $this->tipoVehiculoService->listarTodo();
        $duraciones = $this->precioServicioService->opcionesDuracion((int) old('duracionMinutos', 30));
        return view('precioServicio.crear', compact('servicios', 'tipoVehiculos', 'duraciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrecioServicioStoreRequest $precioServicioStoreRequest)
    {
        $this->precioServicioService->guardar($precioServicioStoreRequest->validated());
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
        $duracionActual = (int) old('duracionMinutos', $precioServicio->duracionMinutos ?? 30);
        $duraciones = $this->precioServicioService->opcionesDuracion($duracionActual);
        return view('precioServicio.editar', compact('precioServicio', 'servicios', 'tipoVehiculos', 'duraciones', 'duracionActual'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrecioServicioUpdateRequest $precioServicioUpdateRequest, int $id)
    {
        $this->precioServicioService->actualizar($id, $precioServicioUpdateRequest->validated());
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
