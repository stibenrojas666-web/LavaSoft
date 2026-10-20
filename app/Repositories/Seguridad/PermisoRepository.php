<?php

namespace App\Repositories\Seguridad;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

class PermisoRepository
{
    public function listar(): Collection
    {
        return Permission::orderBy('modulo')->orderBy('name')->get();
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
