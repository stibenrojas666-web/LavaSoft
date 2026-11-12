<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RolUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $rol = $this->route('rol');
        $rolId = $rol instanceof Role ? $rol->getKey() : $rol;

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique(config('permission.table_names.roles'), 'name')->where('guard_name', config('seguridad.guard'))->ignore($rolId)],
            'activo' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists(config('permission.table_names.permissions'), 'name')->where('guard_name', config('seguridad.guard'))],
        ];
    }
}
