<?php

namespace App\Http\Controllers;

use App\Models\turno;
use App\Models\empleado;
use Illuminate\Http\Request;
use App\Services\turnoService;
use App\Http\Requests\turnoStoreRequest;
use App\Http\Requests\turnoUpdateRequest;


class TurnoController extends Controller
{

private turnoService $turnoService;
public function __construct(turnoService $turnoService)
{
    $this->turnoService = $turnoService;
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnos = $this->turnoService->listarTodo();
        return view('turno.index', compact('turnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empleados = empleado::all();
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
        $empleados = empleado::all();
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
