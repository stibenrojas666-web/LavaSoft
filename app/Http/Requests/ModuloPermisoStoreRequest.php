<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuloPermisoStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['modulo' => ['required', 'string', 'max:50']];
    }
}
