<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombreCliente'    => 'required|string|max:100',
            'apellidoCliente'  => 'required|string|max:100',
            'telefonoCliente'  => 'required|string|max:20',
            'emailCliente'     => 'required|email|max:150|unique:clientes,emailCliente',
        ];
    }

    public function messages(): array
    {
        return [
            'nombreCliente.required'   => 'El nombre es obligatorio',
            'apellidoCliente.required' => 'El apellido es obligatorio',
            'telefonoCliente.required' => 'El teléfono es obligatorio',
            'emailCliente.required'    => 'El email es obligatorio',
            'emailCliente.email'       => 'El email no es válido',
            'emailCliente.unique'      => 'Este email ya está registrado',
        ];
    }
}