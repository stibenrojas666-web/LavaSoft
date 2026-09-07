<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class clienteUpdateRequest extends FormRequest
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
            'nombreCliente' => 'required|string|max:100',
            'apellidoCliente' => 'required|string|max:100',
            'telefonoCliente' => 'required|string|max:20',
            'emailCliente' => 'required|string|max:100'
        ];

    }
    public function messages(): array
    {
        return [
            'nombreCliente.required' => 'El nombre del cliente es obligatorio.',
            'nombreCliente.string' => 'El nombre del cliente debe ser una cadena de texto.',
            'nombreCliente.max' => 'El nombre del cliente no debe exceder los 100 caracteres.',
            'apellidoCliente.required' => 'El apellido del cliente es obligatorio.',
            'apellidoCliente.string' => 'El apellido del cliente debe ser una cadena de texto.',
            'apellidoCliente.max' => 'El apellido del cliente no debe exceder los 100 caracteres.',
            'telefonoCliente.required' => 'El teléfono del cliente es obligatorio.',
            'telefonoCliente.string' => 'El teléfono del cliente debe ser una cadena de texto.',
            'telefonoCliente.max' => 'El teléfono del cliente no debe exceder los 20 caracteres.',
            'emailCliente.required' => 'El correo electrónico del cliente es obligatorio.',
            'emailCliente.email' => 'El correo electrónico del cliente debe ser una dirección de correo válida.',
            'emailCliente.max' => 'El correo electrónico del cliente no debe exceder los 100 caracteres.',
            'emailCliente.unique' => 'El correo electrónico del cliente ya está en uso.',
        ];
    }
}
