<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignacionRolesRequest;
use App\Services\Seguridad\RolService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsuarioRolController extends Controller
{
    public function __construct(private RolService $rolService)
    {
    }

    public function index(): View
    {
        return view('seguridad.usuarios.index', [
            'usuarios' => $this->rolService->usuariosConRolesParaListado(auth()->user()),
            'roles' => $this->rolService->listar(),
        ]);
    }

    public function update(AsignacionRolesRequest $asignacionRolesRequest): RedirectResponse
    {
        try {
            $this->rolService->asignarRoles($asignacionRolesRequest->validated(), $asignacionRolesRequest->user());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.usuarios.index')->with('success', 'Roles asignados correctamente.');
    }
}
