<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class precioServicioUpdateRequest extends FormRequest
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
        return[
            'servicio_id' => [
                'required', 'exists:servicios,id',
                Rule::unique('precio_servicios', 'servicio_id')
                    ->where('tipo_vehiculo_id', $this->input('tipo_vehiculo_id'))
                    ->ignore($this->route('precioServicio')),
            ],
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'modalidad' => 'required|in:fijo,desde,convenir',
            'precio' => 'nullable|required_if:modalidad,fijo,desde|numeric|min:0.01|max:99999999.99',
        ];
    }

    public function messages(): array
    {
        return[
            'servicio_id.required' => 'Debes seleccionar un servicio.',
            'servicio_id.unique' => 'Ya existe un precio para ese servicio y tipo de vehículo.',
            'tipo_vehiculo_id.required' => 'Debes seleccionar un tipo de vehículo.',
            'modalidad.required' => 'Selecciona cómo se calcula el precio.',
            'modalidad.in' => 'La modalidad de precio no es válida.',
            'precio.required' => 'El campo Precio es obligatorio.',
            'precio.numeric' => 'El campo Precio debe ser un número.',
        ];
    }
}
