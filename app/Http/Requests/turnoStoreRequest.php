<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class turnoStoreRequest extends FormRequest
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
            'empleado_id' => 'required|exists:empleados,id',
            'dia' => 'required|string|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'jornada' => 'required|string|in:mañana,tarde,noche',
        ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.required' => 'Debes seleccionar un empleado.',
            'empleado_id.exists' => 'El empleado seleccionado no es válido.',
            'dia.required' => 'Debes seleccionar el día.',
            'dia.in' => 'El día seleccionado no es válido.',
            'jornada.required' => 'Debes seleccionar la jornada.',
            'jornada.in' => 'La jornada seleccionada no es válida.',
        ];
    }
}
