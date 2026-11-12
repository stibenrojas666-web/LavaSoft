<?php

namespace App\Services\Seguridad;

use App\Repositories\Seguridad\Contracts\PermisoRepositoryInterface as PermisoRepository;
use App\Repositories\Seguridad\Contracts\RolRepositoryInterface as RolRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function buscar(string $texto): LengthAwarePaginator
    {
        return $this->permisoRepository->buscar($texto);
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));

        return [
            'permisos' => $this->buscar($buscar),
            'modulos' => $this->listarModulos(),
            'acciones' => config('seguridad.acciones'),
            'buscar' => $buscar,
        ];
    }

    public function listarModulos(): array
    {
        return $this->permisoRepository->listarModulos();
    }

    public function crear(array $datos): void
    {
        $permiso = $this->permisoRepository->crear(
            $datos['name'],
            $this->normalizarModulo($datos['modulo'] ?? null)
        );

        $this->darPermisoAlAdministrador([$permiso]);
        $this->limpiarCache();
    }

    public function crearModulo(array $datos): int
    {
        $modulo = $this->normalizarModulo($datos['modulo']);
        $permisos = [];

        foreach (config('seguridad.acciones') as $accion) {
            $permisos[] = $this->permisoRepository->crearSiNoExiste($accion . ' ' . $modulo, $modulo);
        }

        $this->darPermisoAlAdministrador($permisos);
        $this->limpiarCache();

        return count($permisos);
    }

    public function actualizar(Permission $permiso, array $datos): void
{
    $nombre = $datos['name'];
    $modulo = $datos['modulo'] ?? null;
    if ($permiso->guard_name !== config('seguridad.guard')) {
        throw new Exception('No puedes modificar permisos de otro guard.');
    }

    if ($permiso->name === config('seguridad.permiso_admin') && $nombre !== $permiso->name) {
        throw new Exception('El permiso "' . $permiso->name . '" no se puede renombrar.');
    }

    if ($permiso->name !== $nombre && $this->permisoRepository->tieneRolesAsignados($permiso)) {
        throw new Exception('No puedes renombrar un permiso que está asignado a uno o más roles.');
    }

    $this->permisoRepository->actualizar(
        $permiso,
        $nombre,
        $this->normalizarModulo($modulo)
    );

    $this->limpiarCache();
}

    public function eliminar(Permission $permiso): void
{
    if ($permiso->guard_name !== config('seguridad.guard')) {
        throw new Exception('No puedes eliminar permisos de otro guard.');
    }

    if ($permiso->name === config('seguridad.permiso_admin')) {
        throw new Exception('El permiso "' . $permiso->name . '" no se puede eliminar.');
    }

    if ($this->permisoRepository->tieneRolesAsignados($permiso)) {
        throw new Exception('No puedes eliminar un permiso que está asignado a uno o más roles.');
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
