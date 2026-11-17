<?php

namespace App\Http\Controllers;

use App\Services\ServicioService;
use App\Http\Requests\ServicioStoreRequest;
use App\Http\Requests\ServicioUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;

class ServicioController extends Controller
{
    private ServicioService $servicioService;

    public function __construct(ServicioService $servicioService)
    {
        $this->servicioService = $servicioService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('servicios.index', $this->servicioService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicios.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServicioStoreRequest $servicioStoreRequest)
    {
        $this->servicioService->guardar($servicioStoreRequest->validated());
        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        $activo = $this->servicioService->cambiarEstado($id);

        return redirect()->route('servicios.index', request()->query())->with(
            'success',
            $activo ? 'Servicio activado correctamente.' : 'Servicio desactivado correctamente.'
        );
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
        $servicio = $this->servicioService->buscarPorId($id);
        return view('servicios.editar', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServicioUpdateRequest $servicioUpdateRequest, int $id)
    {
        $this->servicioService->actualizar($id, $servicioUpdateRequest->validated());
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$this->servicioService->eliminar($id)) {
            return redirect()->route('servicios.index')->with('error', 'No se puede eliminar el servicio porque tiene citas o precios relacionados.');
        }
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente');
    }
}
