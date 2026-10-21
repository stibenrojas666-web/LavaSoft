<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class turnoUpdateRequest extends FormRequest
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
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.required' => 'Debes seleccionar un empleado.',
            'dia.required' => 'Debes seleccionar el día.',
            'jornada.required' => 'Debes seleccionar la jornada.',
            'horaInicio.required' => 'Debes ingresar la hora de inicio.',
            'horaInicio.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'horaFin.required' => 'Debes ingresar la hora de fin.',
            'horaFin.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'horaFin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'empleado_id.exists' => 'El empleado seleccionado no es válido.',
            'dia.in' => 'El día seleccionado no es válido.',
            'jornada.in' => 'La jornada seleccionada no es válida.',
        ];
    }
}
