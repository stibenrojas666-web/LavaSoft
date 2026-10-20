<?php

namespace App\Repositories\Seguridad;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RolRepository
{
    public function listar(): Collection
    {
        return Role::with('permissions')->withCount('users')->orderBy('name')->get();
    }

    public function listarNombres(): array
    {
        return Role::orderBy('name')->pluck('name')->all();
    }

    public function crear(string $nombre, bool $activo, array $permisos): Role
    {
        $rol = Role::create([
            'name' => $nombre,
            'guard_name' => config('seguridad.guard'),
            'activo' => $activo,
        ]);

        $rol->syncPermissions($permisos);

        return $rol;
    }

    public function actualizar(Role $rol, string $nombre, bool $activo, array $permisos): void
    {
        $rol->update(['name' => $nombre, 'activo' => $activo]);
        $rol->syncPermissions($permisos);
    }

    public function cambiarEstado(Role $rol, bool $activo): void
    {
        $rol->update(['activo' => $activo]);
    }

    public function eliminar(Role $rol): void
    {
        $rol->delete();
    }

    public function contarUsuarios(Role $rol): int
    {
        return $rol->users()->count();
    }

    public function buscarPorNombre(string $nombre): ?Role
    {
        return Role::where('name', $nombre)->where('guard_name', config('seguridad.guard'))->first();
    }
}
