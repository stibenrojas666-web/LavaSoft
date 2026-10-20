<?php

namespace App\Services\Seguridad;

use App\Repositories\Seguridad\PermisoRepository;
use App\Repositories\Seguridad\RolRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermisoService
{
    public function __construct(
        private PermisoRepository $permisoRepository,
        private RolRepository $rolRepository,
    ) {
    }

    public function listar(): Collection
    {
        return $this->permisoRepository->listar();
    }

    public function listarModulos(): array
    {
        return $this->permisoRepository->listarModulos();
    }

    public function crear(string $nombre, ?string $modulo): void
    {
        $permiso = $this->permisoRepository->crear($nombre, $this->normalizarModulo($modulo));

        $this->darPermisoAlAdministrador([$permiso]);
        $this->limpiarCache();
    }

    public function crearModulo(string $modulo): int
    {
        $modulo = $this->normalizarModulo($modulo);
        $permisos = [];

        foreach (config('seguridad.acciones') as $accion) {
            $permisos[] = $this->permisoRepository->crearSiNoExiste($accion . ' ' . $modulo, $modulo);
        }

        $this->darPermisoAlAdministrador($permisos);
        $this->limpiarCache();

        return count($permisos);
    }

    public function actualizar(Permission $permiso, string $nombre, ?string $modulo): void
    {
        if ($permiso->name === config('seguridad.permiso_admin') && $nombre !== $permiso->name) {
            throw new Exception('El permiso "' . $permiso->name . '" no se puede renombrar.');
        }

        $this->permisoRepository->actualizar($permiso, $nombre, $this->normalizarModulo($modulo));
        $this->limpiarCache();
    }

    public function eliminar(Permission $permiso): void
    {
        if ($permiso->name === config('seguridad.permiso_admin')) {
            throw new Exception('El permiso "' . $permiso->name . '" no se puede eliminar.');
        }

        $this->permisoRepository->eliminar($permiso);
        $this->limpiarCache();
    }

    private function normalizarModulo(?string $modulo): ?string
    {
        $modulo = $modulo === null ? '' : trim($modulo);

        return $modulo === '' ? null : mb_strtolower($modulo);
    }

    private function darPermisoAlAdministrador(array $permisos): void
    {
        $administrador = $this->rolRepository->buscarPorNombre(config('seguridad.rol_admin'));

        $administrador?->givePermissionTo($permisos);
    }

    private function limpiarCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
