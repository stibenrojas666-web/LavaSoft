<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

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
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            ];
    }

    /** Evita que un empleado tenga dos turnos semanales que se crucen el mismo día. */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('empleado_id') || $validator->errors()->has('dia') ||
                $validator->errors()->has('horaInicio') || $validator->errors()->has('horaFin')) {
                return;
            }

            $inicio = $this->input('horaInicio') . ':00';
            $fin = $this->input('horaFin') . ':00';

            $seCruza = DB::table('turnos')
                ->where('empleado_id', $this->input('empleado_id'))
                ->where('dia', $this->input('dia'))
                ->where('horaInicio', '<', $fin)
                ->where('horaFin', '>', $inicio)
                ->exists();

            if ($seCruza) {
                $validator->errors()->add('horaInicio', 'Este empleado ya tiene un turno que se cruza con ese horario en ese día.');
            }
        });
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
            'horaInicio.required' => 'Debes ingresar la hora de inicio.',
            'horaInicio.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'horaFin.required' => 'Debes ingresar la hora de fin.',
            'horaFin.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'horaFin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            ];
    }
}
