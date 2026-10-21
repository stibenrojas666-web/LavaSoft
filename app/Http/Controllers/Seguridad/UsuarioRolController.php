<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Services\Seguridad\RolService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuarioRolController extends Controller
{
    public function __construct(private RolService $rolService)
    {
    }

    public function index(): View
    {
        return view('seguridad.usuarios.index', [
            'usuarios' => $this->rolService->listarUsuariosConRoles(),
            'roles' => $this->rolService->listar(),
            'rolAdmin' => config('seguridad.rol_admin'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['array'],
            'roles.*.*' => ['string', Rule::exists(config('permission.table_names.roles'), 'name')],
        ]);

        try {
            $this->rolService->asignarRoles($datos['roles'] ?? [], $request->user());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.usuarios.index')->with('success', 'Roles asignados correctamente.');
    }
}
