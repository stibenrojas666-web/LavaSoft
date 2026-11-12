<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermisoUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $permiso = $this->route('permiso');
        $permisoId = $permiso instanceof Permission ? $permiso->getKey() : $permiso;

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique(config('permission.table_names.permissions'), 'name')->where('guard_name', config('seguridad.guard'))->ignore($permisoId)],
            'modulo' => ['nullable', 'string', 'max:50'],
        ];
    }
}
