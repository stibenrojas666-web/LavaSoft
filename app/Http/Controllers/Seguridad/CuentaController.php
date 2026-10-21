<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Services\Seguridad\CuentaService;
use App\Http\Requests\BusquedaIndexRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function __construct(private CuentaService $cuentaService)
    {
    }

    public function index(BusquedaIndexRequest $request): View
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        return view('seguridad.cuentas.index', [
            'usuarios' => $this->cuentaService->buscarEnListado($buscar),
            'buscar' => $buscar,
        ]);
    }

    public function create(): View
    {
        return view('seguridad.cuentas.form', [
            'usuario' => null,
            'roles' => $this->cuentaService->listarRoles(),
            'seleccionados' => old('roles', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(config('auth.providers.users.model'), 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists(config('permission.table_names.roles'), 'name')],
        ]);

        $this->cuentaService->crear(Arr::only($datos, ['name', 'email', 'password']), $datos['roles'] ?? []);

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(string $cuenta): View
    {
        $usuario = $this->cuentaService->buscar($cuenta);

        return view('seguridad.cuentas.form', [
            'usuario' => $usuario,
            'roles' => $this->cuentaService->listarRoles(),
            'seleccionados' => old('roles', $usuario->rolesConInactivos->pluck('name')->all()),
        ]);
    }

    public function update(Request $request, string $cuenta): RedirectResponse
    {
        $usuario = $this->cuentaService->buscar($cuenta);

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(config('auth.providers.users.model'), 'email')->ignore($usuario->getKey())],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists(config('permission.table_names.roles'), 'name')],
        ]);

        try {
            $this->cuentaService->actualizar(
                $usuario,
                Arr::only($datos, ['name', 'email', 'password']),
                $datos['roles'] ?? [],
                $request->user()
            );
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, string $cuenta): RedirectResponse
    {
        try {
            $this->cuentaService->eliminar($this->cuentaService->buscar($cuenta), $request->user());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
