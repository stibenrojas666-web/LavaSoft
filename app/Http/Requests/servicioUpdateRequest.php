<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class servicioUpdateRequest extends FormRequest
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
            'Nombre' => 'string|max:255',
            'Precio' => 'numeric',
            'Descripcion' => 'string|nullable',
            'Activo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'Nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'Nombre.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
            'Precio.numeric' => 'El campo Precio debe ser un número.',
            'Descripcion.string' => 'El campo Descripcion debe ser una cadena de texto.',
            'Activo.boolean' => 'El campo Activo debe ser verdadero o falso.',
        ];
    }
}
