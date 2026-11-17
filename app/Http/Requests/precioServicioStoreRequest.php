<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrecioServicioStoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->input('modalidad') === 'convenir') {
            $this->merge(['precio' => null]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'servicio_id' => [
                'required',
                'exists:servicios,id',
                Rule::unique('precio_servicios', 'servicio_id')
                    ->where('tipo_vehiculo_id', $this->input('tipo_vehiculo_id')),
            ],

            'tipo_vehiculo_id' => [
                'required',
                'exists:tipo_vehiculos,id',
            ],

            'modalidad' => [
                'required',
                'in:fijo,desde,convenir',
            ],

            'precio' => [
                'nullable',
                'required_if:modalidad,fijo,desde',
                'numeric',
                'min:0.01',
                'max:99999999.99',
            ],

            'duracionMinutos' => [
                'required',
                'integer',
                'min:1',
                'max:1440',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'servicio_id.required' => 'Debes seleccionar un servicio.',
            'servicio_id.exists' => 'El servicio seleccionado no es válido.',
            'servicio_id.unique' => 'Ya existe un precio para ese servicio y tipo de vehículo.',

            'tipo_vehiculo_id.required' => 'Debes seleccionar un tipo de vehículo.',
            'tipo_vehiculo_id.exists' => 'El tipo de vehículo seleccionado no es válido.',

            'modalidad.required' => 'Selecciona cómo se calcula el precio.',
            'modalidad.in' => 'La modalidad de precio no es válida.',

            'precio.required_if' => 'Ingresa un precio para esta modalidad.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor que cero.',
            'precio.max' => 'El precio supera el valor permitido.',

            'duracionMinutos.required' => 'Debes indicar cuánto dura el servicio para este vehículo.',
            'duracionMinutos.integer' => 'La duración debe expresarse en minutos completos.',
            'duracionMinutos.min' => 'La duración debe ser de al menos un minuto.',
            'duracionMinutos.max' => 'La duración no puede superar las 24 horas.',
        ];
    }
}