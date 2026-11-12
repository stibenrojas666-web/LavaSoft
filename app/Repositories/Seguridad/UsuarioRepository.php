<?php

namespace App\Repositories\Seguridad;

use App\Repositories\Seguridad\Contracts\UsuarioRepositoryInterface;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use App\Support\TextoBusqueda;

class UsuarioRepository implements UsuarioRepositoryInterface {
    private function modelo(): string
    {
        return config('auth.providers.users.model');
    }

    public function buscarPorEmail(string $email): ?User
    {
        return $this->modelo()::where('email', $email)->first();
    }

    public function buscarPorId(int|string $id): User
    {
        return $this->modelo()::findOrFail($id);
    }

    public function crear(array $datos): User 
    {
        return $this->modelo()::create($datos);
    }

    public function actualizar(User $usuario, array $datos): void
    {
        $usuario->update($datos);
    }

    public function eliminar(User $usuario): void
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
        return $this->modelo()::query()
            ->select(['id', 'name', 'email'])
            ->with('rolesConInactivos:id,name,guard_name,activo')
            ->orderBy('name')
            ->get();
    }

    public function buscarConRoles(string $texto)
    {
        $consulta = TextoBusqueda::aplicar($this->modelo()::query()
            ->select(['id', 'name', 'email'])
            ->with('rolesConInactivos:id,name,guard_name,activo'), $texto, fn ($filtros, $termino) => $filtros
            ->where('name', 'like', $termino)
            ->orWhere('email', 'like', $termino)
            ->orWhereHas('rolesConInactivos', fn ($rol) => $rol->where('name', 'like', $termino)));

        return $consulta->orderBy('name')->paginate(10)->withQueryString();
    }

    public function sincronizarRoles(User $usuario, array $nombresDeRoles): void
{
    $rolesActualesInactivos = $usuario->rolesConInactivos()
        ->where('activo', false)
        ->whereIn('name', $nombresDeRoles)
        ->pluck('id')
        ->all();

    $rolesActivos = Role::whereIn('name', $nombresDeRoles)
        ->where('guard_name', config('seguridad.guard'))
        ->where('activo', true)
        ->pluck('id')
        ->all();

    $ids = array_unique(array_merge(
        $rolesActivos,
        $rolesActualesInactivos
    ));

    $usuario->rolesConInactivos()->sync($ids);

    $usuario->unsetRelation('roles');
    $usuario->unsetRelation('rolesConInactivos');
}
}
