<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class vehiculoUpdateRequest extends FormRequest
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
            'clienteID'=>'required|exists:clientes,id',
            'tipoVehiculoId'=>'required|exists:tipo_vehiculos,id',
            'placa' => [
                'required', 'string', 'max:20',
                Rule::unique('vehiculos', 'placa')->ignore($this->route('vehiculo')),
            ],
            'modelo'=>'required|string|max:100',
            'color'=>'required|string|max:50',
            'estado'=>'required|string|max:50',
        ];
    }

    public function messages(): array{
    return [
                'clienteID.required'=>'El cliente es obligatorio',
                'clienteID.exists'=>'El cliente seleccionado no existe',
                'tipoVehiculoId.required'=>'El tipo de vehículo es obligatorio',
                'tipoVehiculoId.exists'=>'El tipo de vehículo seleccionado no existe',
                'placa.required'=>'La placa es obligatoria',
                'placa.string'=>'La placa debe ser texto.',
                'placa.max'=>'La placa no puede superar los 20 caracteres.',
                'placa.unique'=>'La placa ya se encuentra registrada',
                'modelo.required'=>'El modelo es obligatorio',
                'modelo.string'=>'El modelo debe ser texto.',
                'modelo.max'=>'El modelo no puede superar los 100 caracteres.',
                'color.required'=>'El color es obligatorio',
                'color.string'=>'El color debe ser texto.',
                'color.max'=>'El color no puede superar los 50 caracteres.',
                'estado.required'=>'El estado es obligatorio',
                'estado.string'=>'El estado debe ser texto.',
                'estado.max'=>'El estado no puede superar los 50 caracteres.',
        ];
    }
    
}
