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
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'precio.numeric' => 'El campo Precio debe ser un número.',
        ];
    }
}
