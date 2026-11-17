<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadosDisponiblesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'agendaId' => ['nullable', 'integer', 'exists:agendas,id'],
            'duracionMinutos' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'hora.required' => 'La hora es obligatoria.',
            'hora.date_format' => 'La hora debe tener el formato HH:MM.',
            'agendaId.integer' => 'La cita seleccionada no es válida.',
            'agendaId.exists' => 'La cita seleccionada no existe.',
            'duracionMinutos.integer' => 'La duración debe ser un número entero.',
            'duracionMinutos.min' => 'La duración debe ser de al menos un minuto.',
            'duracionMinutos.max' => 'La duración no puede superar las 24 horas.',
        ];
    }
}