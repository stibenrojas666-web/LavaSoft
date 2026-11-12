<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermisoStoreRequest;
use App\Http\Requests\ModuloPermisoStoreRequest;
use App\Http\Requests\PermisoUpdateRequest;
use App\Services\Seguridad\PermisoService;
use App\Http\Requests\BusquedaIndexRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    public function __construct(private PermisoService $permisoService)
    {
    }

    public function index(BusquedaIndexRequest $busquedaIndexRequest): View
    {
        return view('seguridad.permisos.index', $this->permisoService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    public function store(PermisoStoreRequest $permisoStoreRequest): RedirectResponse
    {
        $this->permisoService->crear($permisoStoreRequest->validated());

        return redirect()->route('seguridad.permisos.index')->with('success', 'Permiso creado correctamente.');
    }

    public function storeModulo(ModuloPermisoStoreRequest $moduloPermisoStoreRequest): RedirectResponse
    {
        $total = $this->permisoService->crearModulo($moduloPermisoStoreRequest->validated());

        return redirect()->route('seguridad.permisos.index')
            ->with('success', 'Módulo listo: ' . $total . ' permisos estándar creados o actualizados.');
    }

    public function update(PermisoUpdateRequest $permisoUpdateRequest, Permission $permiso): RedirectResponse
    {
        try {
            $this->permisoService->actualizar($permiso, $permisoUpdateRequest->validated());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.permisos.index')->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permiso): RedirectResponse
    {
        try {
            $this->permisoService->eliminar($permiso);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.permisos.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
