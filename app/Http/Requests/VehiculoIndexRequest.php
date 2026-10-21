<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehiculoIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'buscar' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'in:activo,inactivo'],
            'tipoVehiculoId' => ['nullable', 'integer', 'exists:tipo_vehiculos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'buscar.string' => 'La búsqueda debe ser texto.',
            'buscar.max' => 'La búsqueda no puede superar los 100 caracteres.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'tipoVehiculoId.integer' => 'El tipo de vehículo seleccionado no es válido.',
            'tipoVehiculoId.exists' => 'El tipo de vehículo seleccionado ya no existe.',
        ];
    }
}
