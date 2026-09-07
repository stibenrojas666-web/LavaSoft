<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class servicioStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Nombre' => 'required|string|max:255',
            'Precio' => 'required|numeric',
            'Descripcion' => 'nullable|string',
            'Activo' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'Precio.required' => 'El campo Precio es obligatorio.',
            'Activo.required' => 'El campo Activo es obligatorio.',
        ];
    }
}
