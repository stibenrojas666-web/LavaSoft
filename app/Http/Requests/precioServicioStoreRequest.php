<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class precioServicioStoreRequest extends FormRequest
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
            'servicio_id' => 'required|exists:servicios,id',
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'precio' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'servicio_id.required' => 'Debes seleccionar un servicio.',
            'servicio_id.exists' => 'El servicio seleccionado no es válido.',
            'tipo_vehiculo_id.required' => 'Debes seleccionar un tipo de vehículo.',
            'tipo_vehiculo_id.exists' => 'El tipo de vehículo seleccionado no es válido.',
            'precio.required' => 'El campo Precio es obligatorio.',
            'precio.numeric' => 'El campo Precio debe ser un número.',
        ];
    }
}
