<?php

namespace App\Http\Controllers;

use app\Http\Requests\EmpleadoStoreRequest;
use app\Http\Requests\EmpleadoUpdateRequest;
use App\Models\Empleado;
use App\Services\EmpleadoService;

class EmpleadosController extends Controller
{
    private EmpleadoService $empleadosService;

    public function __construct(EmpleadoService $empleadosService)
    {
        $this->empleadosService = $empleadosService;
    } 
    public function index()
    {
        $empleados = $this->empleadosService->listarTodo();
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
    public function store(EmpleadosStoreRequest $request)
    {
        $this ->empleadosService->guardar($request->validated());

        return redirect()->route('empleados.index')->with('success', 'Empleado guardado con exito');
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
        $empleado = $this->empleadosService->buscarporId($id);
        return view('empleados.editar', compact('empleado'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, EmpleadosStoreRequest $request)
    {
        $this->empleadosService->actualizar($id, $request->validated());
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->empleadosService->eliminar($id);
        return redirect()->route('empleados.index')->with('success','empleado eliminado con exito');
    }
}
