<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

trait TieneRolesActivos
{
    use HasRoles {
        roles as private rolesSinFiltro;
    }

    public function roles(): BelongsToMany
    {
        return $this->rolesSinFiltro()
            ->where(config('permission.table_names.roles') . '.activo', true);
    }

    public function rolesConInactivos(): BelongsToMany
    {
        return $this->rolesSinFiltro();
    }
}
