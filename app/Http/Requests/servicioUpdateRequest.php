<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServicioUpdateRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:80', Rule::unique('servicios', 'nombre')->ignore($this->route('servicio'))],
            'descripcion' => 'nullable|string|max:250',
            'duracionMinutos' => ['required', 'integer', 'min:5', 'max:480'],
            'estado' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del servicio no puede superar los 80 caracteres.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede superar los 250 caracteres.',
            'duracionMinutos.required' => 'La duración del servicio es obligatoria.',
            'duracionMinutos.integer' => 'La duración debe ser un número entero de minutos.',
            'duracionMinutos.min' => 'La duración mínima es de 5 minutos.',
            'duracionMinutos.max' => 'La duración máxima es de 480 minutos.',
            'estado.boolean' => 'El estado seleccionado no es válido.',
        ];
    }
}
