<?php


namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolStoreRequest;
use App\Http\Requests\RolUpdateRequest;
use App\Services\Seguridad\PermisoService;
use App\Services\Seguridad\RolService;
use App\Http\Requests\BusquedaIndexRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    public function __construct(
        private RolService $rolService,
        private PermisoService $permisoService,
    ) {
    }

    public function index(BusquedaIndexRequest $busquedaIndexRequest): View
    {
        return view('seguridad.roles.index', $this->rolService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
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

    public function store(RolStoreRequest $rolStoreRequest): RedirectResponse
    {
        $this->rolService->crear($rolStoreRequest->validated());

        return redirect()->route('seguridad.roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $rol): View
    {
        return view('seguridad.roles.form', [
            'rol' => $rol,
            'protegido' => $this->rolService->esProtegido($rol),
            'noDesactivable' => $this->rolService->esNoDesactivable($rol),
            'permisos' => $this->permisoService->listar(),
            'seleccionados' => old('permissions', $this->rolService->permisosSeleccionados($rol)),
        ]);
    }

    public function update(RolUpdateRequest $rolUpdateRequest, Role $rol): RedirectResponse
    {
        try {
            $this->rolService->actualizar($rol, $rolUpdateRequest->validated());
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
