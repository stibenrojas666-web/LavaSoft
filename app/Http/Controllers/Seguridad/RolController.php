<?php


namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Services\Seguridad\PermisoService;
use App\Services\Seguridad\RolService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    public function __construct(
        private RolService $rolService,
        private PermisoService $permisoService,
    ) {
    }

    public function index(): View
    {
        return view('seguridad.roles.index', [
            'roles' => $this->rolService->listar(),
            'rolService' => $this->rolService,
        ]);
    }

    public function create(): View
    {
        return view('seguridad.roles.form', [
            'rol' => null,
            'protegido' => false,
            'noDesactivable' => false,
            'permisos' => $this->permisoService->listar(),
            'seleccionados' => old('permissions', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique(config('permission.table_names.roles'), 'name')],
            'activo' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists(config('permission.table_names.permissions'), 'name')],
        ]);

        $this->rolService->crear($datos['name'], (bool) $datos['activo'], $datos['permissions'] ?? []);

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $rol): View
    {
        return view('seguridad.roles.form', [
            'rol' => $rol,
            'protegido' => $this->rolService->esProtegido($rol),
            'noDesactivable' => $this->rolService->esNoDesactivable($rol),
            'permisos' => $this->permisoService->listar(),
            'seleccionados' => old('permissions', $rol->permissions()->pluck('name')->all()),
        ]);
    }

    public function update(Request $request, Role $rol): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique(config('permission.table_names.roles'), 'name')->ignore($rol->id)],
            'activo' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists(config('permission.table_names.permissions'), 'name')],
        ]);

        try {
            $this->rolService->actualizar($rol, $datos['name'], (bool) $datos['activo'], $datos['permissions'] ?? []);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function estado(Role $rol): RedirectResponse
    {
        try {
            $activo = $this->rolService->cambiarEstado($rol);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'El rol "' . $rol->name . '" quedó ' . ($activo ? 'activo' : 'inactivo') . '.');
    }

    public function destroy(Role $rol): RedirectResponse
    {
        try {
            $this->rolService->eliminar($rol);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
