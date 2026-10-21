<?php

namespace App\Http\Controllers;

use App\Services\turnoService;
use App\Services\empleadoService;
use App\Http\Requests\turnoStoreRequest;
use App\Http\Requests\turnoUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;


class TurnoController extends Controller
{

private turnoService $turnoService;
private empleadoService $empleadoService;
public function __construct(turnoService $turnoService, empleadoService $empleadoService)
{
    $this->turnoService = $turnoService;
    $this->empleadoService = $empleadoService;
}
    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $request)
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['dia', 'jornada']));
        $turnos = $this->turnoService->buscar($buscar, $filtros);
        return view('turno.index', compact('turnos', 'buscar', 'filtros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empleados = $this->empleadoService->listarTodo();
        return view('turno.crear', compact('empleados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(turnoStoreRequest $request)
    {
        $datos = $request->validated();
        $this->turnoService->guardar($datos);
        return redirect()->route('turnos.index')->with('success','Turno creado correctamente.');
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
        $turno = $this->turnoService->buscarPorId($id);
        $empleados = $this->empleadoService->listarTodo();
        return view('turno.editar', compact('turno', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(turnoUpdateRequest $request, int $id)
    {
        $this->turnoService->actualizar($id, $request->validated());
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
