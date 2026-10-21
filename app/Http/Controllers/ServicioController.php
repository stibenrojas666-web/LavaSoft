<?php

namespace App\Http\Controllers;

use App\Services\servicioService;
use App\Http\Requests\servicioStoreRequest;
use App\Http\Requests\servicioUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;

class ServicioController extends Controller
{
    private servicioService $servicioService;

    public function __construct(servicioService $servicioService)
    {
        $this->servicioService = $servicioService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $request)
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado']));
        $servicios = $this->servicioService->buscar($buscar, $filtros);
        return view('servicios.index', compact('servicios', 'buscar', 'filtros'));
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
    public function store(servicioStoreRequest $request)
    {
        $datos = $request->validated();
        $this->servicioService->guardar($datos);
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
    public function update(servicioUpdateRequest $request, int $id)
    {
        $this->servicioService->actualizar($id, $request->validated());
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
