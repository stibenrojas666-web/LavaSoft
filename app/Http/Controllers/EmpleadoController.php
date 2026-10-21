<?php

namespace App\Http\Controllers;

use App\Services\empleadoService;
use App\Http\Requests\empleadoStoreRequest;
use App\Http\Requests\empleadoUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;

class EmpleadoController extends Controller
{
    private empleadoService $empleadoService;

    public function __construct(empleadoService $empleadoService)
    {
        $this->empleadoService = $empleadoService;
    }

    public function index(BusquedaIndexRequest $request)
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado']));
        $empleados = $this->empleadoService->buscar($buscar, $filtros);
        return view('empleados.index', compact('empleados', 'buscar', 'filtros'));
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

    public function cambiarEstado(int $id)
    {
        $activo = $this->empleadoService->cambiarEstado($id);

        return redirect()->route('empleados.index')->with(
            'success',
            $activo ? 'Empleado activado correctamente.' : 'Empleado desactivado correctamente.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$this->empleadoService->eliminar($id)) {
            return redirect()->route('empleados.index')->with('error', 'No se puede eliminar el empleado porque tiene citas o turnos relacionados.');
        }
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
