<?php

namespace App\Http\Controllers;

use App\Models\empleado;
use Illuminate\Http\Request;
use App\Services\empleadoService;
use App\Http\Requests\empleadoStoreRequest;
use App\Http\Requests\empleadoUpdateRequest;

class EmpleadoController extends Controller
{
    private empleadoService $empleadoService;

    public function __construct(empleadoService $empleadoService)
    {
        $this->empleadoService = $empleadoService;
    }

    public function index()
    {
        $empleados = $this->empleadoService->listarTodo();
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empleados.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(empleadoStoreRequest $request)
    {
        $datos = $request->validated();
        $this->empleadoService->guardar($datos);
        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');

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
        $empleado = $this->empleadoService->buscarPorId($id);
        return view('empleados.editar', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(empleadoUpdateRequest $request, int $id)
{
    $this->empleadoService->actualizar($id, $request->validated());
    return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->empleadoService->eliminar($id);
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}