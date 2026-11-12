<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\CuentaStoreRequest;
use App\Http\Requests\CuentaUpdateRequest;
use App\Services\Seguridad\CuentaService;
use App\Http\Requests\BusquedaIndexRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function __construct(private CuentaService $cuentaService)
    {
    }

    public function index(BusquedaIndexRequest $busquedaIndexRequest): View
    {
        return view('seguridad.cuentas.index', $this->cuentaService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    public function create(): View
    {
        return view('seguridad.cuentas.form', [
            'usuario' => null,
            'roles' => $this->cuentaService->listarRoles(),
            'seleccionados' => old('roles', []),
        ]);
    }

    public function store(CuentaStoreRequest $cuentaStoreRequest): RedirectResponse
    {
        $this->cuentaService->crear($cuentaStoreRequest->validated());

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(string $cuenta): View
    {
        $usuario = $this->cuentaService->buscar($cuenta);

        return view('seguridad.cuentas.form', [
            'usuario' => $usuario,
            'roles' => $this->cuentaService->listarRoles(),
            'seleccionados' => old('roles', $this->cuentaService->rolesSeleccionados($usuario)),
        ]);
    }

    public function update(CuentaUpdateRequest $cuentaUpdateRequest, string $cuenta): RedirectResponse
    {
        $usuario = $this->cuentaService->buscar($cuenta);

        try {
            $this->cuentaService->actualizar(
                $usuario,
                $cuentaUpdateRequest->validated(),
                $cuentaUpdateRequest->user()
            );
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(\Illuminate\Http\Request $eliminarCuentaRequest, string $cuenta): RedirectResponse
    {
        try {
            $this->cuentaService->eliminar($this->cuentaService->buscar($cuenta), $eliminarCuentaRequest->user());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
