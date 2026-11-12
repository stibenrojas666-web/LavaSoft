<?php

namespace App\Repositories\Seguridad;

use App\Repositories\Seguridad\Contracts\PermisoRepositoryInterface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use App\Support\TextoBusqueda;

class PermisoRepository implements PermisoRepositoryInterface {
    public function listar(): Collection
    {
        return Permission::query()
    ->where('guard_name', config('seguridad.guard'))
    ->select(['id', 'name', 'guard_name', 'modulo']);
    }

    public function buscar(string $texto): LengthAwarePaginator
    {
        return TextoBusqueda::aplicar(Permission::query()->where('guard_name', config('seguridad.guard'))->select(['id', 'name', 'guard_name', 'modulo']), $texto, fn ($filtros, $termino) => $filtros
            ->where('name', 'like', $termino)
            ->orWhere('modulo', 'like', $termino))
            ->orderBy('modulo')->orderBy('name')->paginate(10)->withQueryString();
    }

    public function listarModulos(): array
{
    return Permission::where('guard_name', config('seguridad.guard'))
        ->whereNotNull('modulo')
        ->distinct()
        ->orderBy('modulo')
        ->pluck('modulo')
        ->all();
}
    public function crear(string $nombre, ?string $modulo): Permission
    {
        return Permission::create([
            'name' => $nombre,
            'guard_name' => config('seguridad.guard'),
            'modulo' => $modulo,
        ]);
    }

    public function tieneRolesAsignados(Permission $permiso): bool
    {
        return $permiso->roles()->exists();
    }

    public function crearSiNoExiste(string $nombre, string $modulo): Permission
    {
        $permiso = Permission::firstOrCreate(
            ['name' => $nombre, 'guard_name' => config('seguridad.guard')],
            ['modulo' => $modulo]
        );

        if ($permiso->modulo === null) {
            $permiso->update(['modulo' => $modulo]);
        }

        return $permiso;
    }

    public function actualizar(Permission $permiso, string $nombre, ?string $modulo): void
{
    if ($permiso->guard_name !== config('seguridad.guard')) {
        throw new \Exception('No puedes modificar permisos de otro guard.');
    }

    $permiso->update([
        'name' => $nombre,
        'modulo' => $modulo,
    ]);
}
    public function eliminar(Permission $permiso): void
{
    if ($permiso->guard_name !== config('seguridad.guard')) {
        throw new \Exception('No puedes eliminar permisos de otro guard.');
    }

    $permiso->delete();
}
}
