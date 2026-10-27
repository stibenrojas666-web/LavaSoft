<?php

namespace App\Repositories\Seguridad;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use App\Support\TextoBusqueda;

class PermisoRepository
{
    public function listar(): Collection
    {
        return Permission::query()
            ->select(['id', 'name', 'guard_name', 'modulo'])
            ->orderBy('modulo')
            ->orderBy('name')
            ->get();
    }

    public function buscar(string $texto): LengthAwarePaginator
    {
        return TextoBusqueda::aplicar(Permission::query()->select(['id', 'name', 'guard_name', 'modulo']), $texto, fn ($filtros, $termino) => $filtros
            ->where('name', 'like', $termino)
            ->orWhere('modulo', 'like', $termino))
            ->orderBy('modulo')->orderBy('name')->paginate(10)->withQueryString();
    }

    public function listarModulos(): array
    {
        return Permission::whereNotNull('modulo')
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
        $permiso->update(['name' => $nombre, 'modulo' => $modulo]);
    }

    public function eliminar(Permission $permiso): void
    {
        $permiso->delete();
    }
}
