<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermisoStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique(config('permission.table_names.permissions'), 'name')->where('guard_name', config('seguridad.guard'))],
            'modulo' => ['nullable', 'string', 'max:50'],
        ];
    }
}
