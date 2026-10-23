<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class precioServicioStoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->input('modalidad') === 'convenir') {
            $this->merge(['precio' => null]);
        }
    }

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
            'servicio_id' => [
                'required', 'exists:servicios,id',
                Rule::unique('precio_servicios', 'servicio_id')
                    ->where('tipo_vehiculo_id', $this->input('tipo_vehiculo_id')),
            ],
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'modalidad' => 'required|in:fijo,desde,convenir',
            'precio' => 'nullable|required_if:modalidad,fijo,desde|numeric|min:0.01|max:99999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'servicio_id.required' => 'Debes seleccionar un servicio.',
            'servicio_id.integer' => 'El servicio seleccionado no es válido.',
            'servicio_id.exists' => 'El servicio seleccionado no es válido.',
            'servicio_id.unique' => 'Ya existe un precio para ese servicio y tipo de vehículo.',
            'tipo_vehiculo_id.required' => 'Debes seleccionar un tipo de vehículo.',
            'tipo_vehiculo_id.integer' => 'El tipo de vehículo seleccionado no es válido.',
            'tipo_vehiculo_id.exists' => 'El tipo de vehículo seleccionado no es válido.',
            'modalidad.required' => 'Selecciona cómo se calcula el precio.',
            'modalidad.in' => 'La modalidad de precio no es válida.',
            'precio.required_if' => 'Ingresa un precio para esta modalidad.',
            'precio.numeric' => 'El campo Precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor que cero.',
            'precio.max' => 'El precio supera el valor permitido.',
        ];
    }
}
