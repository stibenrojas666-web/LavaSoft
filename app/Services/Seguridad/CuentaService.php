<?php

namespace App\Services\Seguridad;

use App\Repositories\Seguridad\RolRepository;
use App\Repositories\Seguridad\UsuarioRepository;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class CuentaService
{
    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private RolRepository $rolRepository,
    ) {
    }

    public function listar(): Collection
    {
        return $this->usuarioRepository->listarConRoles();
    }

    public function listarRoles(): Collection
    {
        return $this->rolRepository->listar();
    }

    public function buscar(int|string $id): Authenticatable
    {
        return $this->usuarioRepository->buscarPorId($id)->load('rolesConInactivos');
    }

    public function crear(array $datos, array $roles): void
    {
        $datos['password'] = Hash::make($datos['password']);

        DB::transaction(function () use ($datos, $roles): void {
            $usuario = $this->usuarioRepository->crear($datos);
            $this->usuarioRepository->sincronizarRoles($usuario, $roles);
        });

        $this->limpiarCache();
    }

    public function actualizar(Authenticatable $usuario, array $datos, array $roles, Authenticatable $actual): void
    {
        $rolAdmin = config('seguridad.rol_admin');

        $seQuitaSuRolAdmin = $usuario->is($actual)
            && $usuario->rolesConInactivos->contains('name', $rolAdmin)
            && ! in_array($rolAdmin, $roles, true);

        if ($seQuitaSuRolAdmin) {
            throw new Exception('No puedes quitarte tu propio rol de ' . $rolAdmin . '.');
        }

        if (empty($datos['password'])) {
            unset($datos['password']);
        } else {
            $datos['password'] = Hash::make($datos['password']);
        }

        DB::transaction(function () use ($usuario, $datos, $roles): void {
            $this->usuarioRepository->actualizar($usuario, $datos);
            $this->usuarioRepository->sincronizarRoles($usuario, $roles);
        });

        $this->limpiarCache();
    }

    public function eliminar(Authenticatable $usuario, Authenticatable $actual): void
    {
        if ($usuario->is($actual)) {
            throw new Exception('No puedes eliminar tu propia cuenta.');
        }

        $rolAdmin = config('seguridad.rol_admin');
        $esAdmin = $usuario->rolesConInactivos->contains('name', $rolAdmin);

        if ($esAdmin && $this->usuarioRepository->contarConRol($rolAdmin) <= 1) {
            throw new Exception('No puedes eliminar al único usuario con el rol ' . $rolAdmin . '.');
        }

        $this->usuarioRepository->eliminar($usuario);
        $this->limpiarCache();
    }

    private function limpiarCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
