<?php

namespace App\Services\Seguridad;

use App\Repositories\Seguridad\Contracts\RolRepositoryInterface as RolRepository;
use App\Repositories\Seguridad\Contracts\UsuarioRepositoryInterface as UsuarioRepository;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolService
{
    public function __construct(
        private RolRepository $rolRepository,
        private UsuarioRepository $usuarioRepository,
    ) {
    }

    public function listar(): Collection
    {
        return $this->rolRepository->listar();
    }

    public function buscar(string $texto)
    {
        return $this->rolRepository->buscar($texto);
    }

    public function buscarParaListado(string $texto)
    {
        $roles = $this->buscar($texto);
        $roles->getCollection()->each(function (Role $rol): void {
            $rol->setAttribute('protegidoParaSistema', $this->esProtegido($rol));
            $rol->setAttribute('noDesactivable', $this->esNoDesactivable($rol));
        });

        return $roles;
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));

        return [
            'roles' => $this->buscarParaListado($buscar),
            'buscar' => $buscar,
        ];
    }

    public function permisosSeleccionados(Role $rol): array
    {
        return $this->rolRepository->permisosSeleccionados($rol);
    }

    public function listarUsuariosConRoles(): Collection
    {
        return $this->usuarioRepository->listarConRoles();
    }

    public function usuariosConRolesParaListado(Authenticatable $usuarioActual): Collection
    {
        $rolAdministrador = config('seguridad.rol_admin');

        return $this->listarUsuariosConRoles()->each(function ($usuario) use ($usuarioActual, $rolAdministrador): void {
            $rolesAsignados = $usuario->rolesConInactivos->pluck('name')->all();
            $usuario->setAttribute('rolesAsignados', $rolesAsignados);
            $usuario->setAttribute('rolesBloqueados', $usuario->is($usuarioActual)
                && in_array($rolAdministrador, $rolesAsignados, true) ? [$rolAdministrador] : []);
        });
    }

    public function esProtegido(Role $rol): bool
    {
        return in_array($rol->name, config('seguridad.roles_protegidos'), true);
    }

    public function esNoDesactivable(Role $rol): bool
    {
        return in_array($rol->name, config('seguridad.roles_no_desactivables'), true);
    }

    public function crear(array $datos): void
    {
        $this->rolRepository->crear(
            $datos['name'],
            (bool) $datos['activo'],
            $datos['permissions'] ?? []
        );
        $this->limpiarCache();
    }

    public function actualizar(Role $rol, array $datos): void
    {
        $nombre = $datos['name'];
        $activo = (bool) $datos['activo'];
        $permisos = $datos['permissions'] ?? [];
        if ($this->esProtegido($rol) && $nombre !== $rol->name) {
            throw new Exception('El rol "' . $rol->name . '" está protegido y no se puede renombrar.');
        }

        if ($this->esNoDesactivable($rol) && ! $activo) {
            throw new Exception('El rol "' . $rol->name . '" no se puede desactivar.');
        }

        $this->rolRepository->actualizar($rol, $nombre, $activo, $permisos);
        $this->limpiarCache();
    }

    public function cambiarEstado(Role $rol): bool
    {
        if ($rol->activo && $this->esNoDesactivable($rol)) {
            throw new Exception('El rol "' . $rol->name . '" no se puede desactivar.');
        }

        $nuevoEstado = ! $rol->activo;

        $this->rolRepository->cambiarEstado($rol, $nuevoEstado);
        $this->limpiarCache();

        return $nuevoEstado;
    }

    public function eliminar(Role $rol): void
    {
        if ($this->esProtegido($rol)) {
            throw new Exception('El rol "' . $rol->name . '" está protegido y no se puede eliminar.');
        }

        if ($this->rolRepository->contarUsuarios($rol) > 0) {
            throw new Exception('No se puede eliminar un rol que tiene usuarios asignados.');
        }

        $this->rolRepository->eliminar($rol);
        $this->limpiarCache();
    }

    public function asignarRoles(array $datos, Authenticatable $actual): void
    {
        $rolesPorUsuario = $datos['roles'] ?? [];
        $rolesSolicitados = collect($rolesPorUsuario)
    ->flatten()
    ->unique()
    ->values();

$rolesActivos = $this->rolRepository->nombresActivos($rolesSolicitados->all());

if ($rolesSolicitados->count() !== count($rolesActivos)) {
    throw new Exception('No puedes asignar roles inexistentes o inactivos.');
}
        $usuarios = $this->usuarioRepository->listarConRoles();
        $rolAdmin = config('seguridad.rol_admin');

        foreach ($usuarios as $usuario) {
            $nuevosRoles = $rolesPorUsuario[$usuario->getKey()] ?? [];

            $seQuitaSuRolAdmin = $usuario->is($actual)
                && $usuario->rolesConInactivos->contains('name', $rolAdmin)
                && ! in_array($rolAdmin, $nuevosRoles, true);

            if ($seQuitaSuRolAdmin) {
                throw new Exception('No puedes quitarte tu propio rol de ' . $rolAdmin . '.');
            }
        }

        DB::transaction(function () use ($usuarios, $rolesPorUsuario): void {
            foreach ($usuarios as $usuario) {
                $this->usuarioRepository->sincronizarRoles($usuario, $rolesPorUsuario[$usuario->getKey()] ?? []);
            }
        });

        $this->limpiarCache();
    }

    private function limpiarCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
