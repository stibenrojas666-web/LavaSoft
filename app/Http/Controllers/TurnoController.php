<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\TurnoStoreRequest;
use App\Http\Requests\TurnoUpdateRequest;
use App\Services\TurnoService;

class TurnoController extends Controller
{
    public function __construct(private TurnoService $turnoService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('turno.index', $this->turnoService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('turno.crear', $this->turnoService->prepararFormulario());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TurnoStoreRequest $turnoStoreRequest)
    {
        $this->turnoService->guardar($turnoStoreRequest->validated());

        return redirect()->route('turnos.index')->with('success', 'Turno creado correctamente.');
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
        return view('turno.editar', $this->turnoService->prepararFormulario($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TurnoUpdateRequest $turnoUpdateRequest, int $id)
    {
        $this->turnoService->actualizar($id, $turnoUpdateRequest->validated());

        return redirect()->route('turnos.index')->with('success', 'Turno actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->turnoService->eliminar($id);

        return redirect()->route('turnos.index')->with('success', 'Turno eliminado correctamente.');
    }
}
