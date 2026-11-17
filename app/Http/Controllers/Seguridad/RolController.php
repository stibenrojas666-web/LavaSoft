<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\RolStoreRequest;
use App\Http\Requests\RolUpdateRequest;
use App\Services\Seguridad\RolService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolController extends Controller
{
    public function __construct(
        private RolService $rolService,
    ) {}

    public function index(BusquedaIndexRequest $busquedaIndexRequest): View
    {
        return view('seguridad.roles.index', $this->rolService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    public function create(): View
    {
        return view('seguridad.roles.form', $this->rolService->prepararFormulario(
            null,
            old('permissions', [])
        ));
    }

    public function store(RolStoreRequest $rolStoreRequest): RedirectResponse
    {
        $this->rolService->crear($rolStoreRequest->validated());

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(int $rol): View
    {
        return view('seguridad.roles.form', $this->rolService->prepararFormulario(
            $rol,
            old('permissions')
        ));
    }

    public function update(RolUpdateRequest $rolUpdateRequest, int $rol): RedirectResponse
    {
        try {
            $this->rolService->actualizarPorId($rol, $rolUpdateRequest->validated());
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function estado(Request $request, int $rol): RedirectResponse
    {
        try {
            $resultado = $this->rolService->cambiarEstadoPorId($rol);
        } catch (Exception $e) {
            return redirect()->to(route('seguridad.roles.index', $request->query()).'#rol-'.$rol)
                ->with('error', $e->getMessage());
        }

        return redirect()->to(route('seguridad.roles.index', $request->query()).'#rol-'.$rol)
            ->with('success', 'El rol "'.$resultado['nombre'].'" quedó '.($resultado['activo'] ? 'activo' : 'inactivo').'.');
    }

    public function destroy(int $rol): RedirectResponse
    {
        try {
            $this->rolService->eliminarPorId($rol);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
