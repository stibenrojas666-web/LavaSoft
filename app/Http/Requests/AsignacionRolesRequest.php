<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AsignacionRolesRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'roles' => ['nullable', 'array'],
            'roles.*' => ['array'],
            'roles.*.*' => ['string', Rule::exists(config('permission.table_names.roles'), 'name')
                ->where('guard_name', config('seguridad.guard'))
                ->where('activo', true)],
        ];
    }
}
