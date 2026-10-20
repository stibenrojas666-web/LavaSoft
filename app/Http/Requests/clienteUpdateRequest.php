<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'telefonoCliente' => ['required', 'regex:/^[0-9]{7,15}$/'],
            'emailCliente' => [
                'required', 'email', 'max:150',
                Rule::unique('clientes', 'emailCliente')->ignore($this->route('cliente')),
            ],
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
            'telefonoCliente.regex' => 'El teléfono debe contener solo números (entre 7 y 15 dígitos).',
            'emailCliente.required' => 'El correo electrónico del cliente es obligatorio.',
            'emailCliente.email' => 'El correo electrónico del cliente debe ser una dirección de correo válida.',
            'emailCliente.max' => 'El correo electrónico del cliente no debe exceder los 100 caracteres.',
            'emailCliente.unique' => 'El correo electrónico del cliente ya está en uso.',
        ];
    }
}
