<?php

namespace App\Http\Controllers;

use App\Services\PrecioServicioService;
use App\Http\Requests\PrecioServicioStoreRequest;
use App\Http\Requests\PrecioServicioUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;

class PrecioServicioController extends Controller 
{
    public function __construct(private PrecioServicioService $precioServicioService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('precioServicio.index', $this->precioServicioService->prepararIndiceConOpciones(
            $busquedaIndexRequest->validated()
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('precioServicio.crear', $this->precioServicioService->prepararFormulario(
            null,
            (int) old('duracionMinutos', 30)
        ));
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
        return view('precioServicio.editar', $this->precioServicioService->prepararFormulario(
            $id,
            old('duracionMinutos') === null ? null : (int) old('duracionMinutos')
        ));
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
