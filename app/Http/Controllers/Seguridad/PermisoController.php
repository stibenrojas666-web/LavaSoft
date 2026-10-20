<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Services\Seguridad\PermisoService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    public function __construct(private PermisoService $permisoService)
    {
    }

    public function index(): View
    {
        return view('seguridad.permisos.index', [
            'permisos' => $this->permisoService->listar(),
            'modulos' => $this->permisoService->listarModulos(),
            'acciones' => config('seguridad.acciones'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(config('permission.table_names.permissions'), 'name')],
            'modulo' => ['nullable', 'string', 'max:50'],
        ]);

        $this->permisoService->crear($datos['name'], $datos['modulo'] ?? null);

        return redirect()->route('seguridad.permisos.index')->with('success', 'Permiso creado correctamente.');
    }

    public function storeModulo(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'modulo' => ['required', 'string', 'max:50'],
        ]);

        $total = $this->permisoService->crearModulo($datos['modulo']);

        return redirect()->route('seguridad.permisos.index')
            ->with('success', 'Módulo listo: ' . $total . ' permisos estándar creados o actualizados.');
    }

    public function update(Request $request, Permission $permiso): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(config('permission.table_names.permissions'), 'name')->ignore($permiso->id)],
            'modulo' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $this->permisoService->actualizar($permiso, $datos['name'], $datos['modulo'] ?? null);
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
