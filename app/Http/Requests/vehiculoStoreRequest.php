<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class vehiculoStoreRequest extends FormRequest
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
        return ['clienteID'=>'required|exists:clientes,id',
                'tipoVehiculoId'=>'required|exists:tipo_vehiculos,id',
                'placa'=>'required|string|max:20|unique:vehiculos,placa',
                'modelo'=>'required|string|max:100',
                'color'=>'required|string|max:50',
                'estado'=>'required|string|max:50',
        ];
    }

    public function messages(): array
    {
    return [
            'clienteID.required'=>'El cliente es obligatorio',
            'clienteID.exists'=>'El cliente seleccionado no existe',
            'tipoVehiculoId.required'=>'El tipo de vehículo es obligatorio',
            'tipoVehiculoId.exists'=>'El tipo de vehículo seleccionado no existe',
            'placa.required'=>'La placa es obligatoria',
            'placa.unique'=>'La placa ya se encuentra registrada',
            'modelo.required'=>'El modelo es obligatorio',
            'color.required'=>'El color es obligatorio',
            'estado.required'=>'El estado es obligatorio',
        ];
    }
}