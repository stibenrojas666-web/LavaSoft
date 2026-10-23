<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class empleadoUpdateRequest extends FormRequest
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
            'nombre' => 'required|string|max:20',
            'apellido' => 'required|string|max:20',
            'identificacion' => 'required|string|max:20',
            'telefono' => 'required|string|max:15',
            'rh' => ['required', Rule::in(config('empleado.tiposSangre'))],
            'eps' => 'required|string|max:50',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 20 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.string' => 'El apellido debe ser texto.',
            'apellido.max' => 'El apellido no puede superar los 20 caracteres.',
            'identificacion.required' => 'La identificación es obligatoria.',
            'identificacion.string' => 'La identificación debe ser texto.',
            'identificacion.max' => 'La identificación no puede superar los 20 caracteres.',
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'telefono.string' => 'El teléfono debe ser texto.',
            'telefono.max' => 'El teléfono no puede superar los 15 caracteres.',
            'rh.required' => 'El campo RH es obligatorio.',
            'rh.in' => 'Selecciona un tipo de sangre válido.',
            'eps.required' => 'El campo EPS es obligatorio.',
            'eps.string' => 'La EPS debe ser texto.',
            'eps.max' => 'La EPS no puede superar los 50 caracteres.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean' => 'El estado seleccionado no es válido.',
        ];
    }
}
