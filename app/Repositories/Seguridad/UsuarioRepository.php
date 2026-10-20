<?php

namespace App\Repositories\Seguridad;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class UsuarioRepository
{
    private function modelo(): string
    {
        return config('auth.providers.users.model');
    }

    public function buscarPorEmail(string $email): ?Authenticatable
    {
        return $this->modelo()::where('email', $email)->first();
    }

    public function buscarPorId(int|string $id): Authenticatable
    {
        return $this->modelo()::findOrFail($id);
    }

    public function crear(array $datos): Authenticatable
    {
        return $this->modelo()::create($datos);
    }

    public function actualizar(Authenticatable $usuario, array $datos): void
    {
        $usuario->update($datos);
    }

    public function eliminar(Authenticatable $usuario): void
    {
        $usuario->delete();
    }

    public function contarConRol(string $rol): int
    {
        return $this->modelo()::whereHas('rolesConInactivos', function ($consulta) use ($rol) {
            $consulta->where(config('permission.table_names.roles') . '.name', $rol);
        })->count();
    }

    public function listarConRoles(): Collection
    {
        return $this->modelo()::with('rolesConInactivos')->orderBy('name')->get();
    }

    public function sincronizarRoles(Authenticatable $usuario, array $nombresDeRoles): void
    {
        $ids = Role::whereIn('name', $nombresDeRoles)
            ->where('guard_name', config('seguridad.guard'))
            ->pluck('id')
            ->all();

        $usuario->rolesConInactivos()->sync($ids);
        $usuario->unsetRelation('roles');
    }
}
