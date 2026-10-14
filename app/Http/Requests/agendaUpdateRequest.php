<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use App\Support\HorarioLavadero;
use Carbon\Carbon;

class AgendaUpdateRequest extends FormRequest
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
            'empleadoId'     => 'nullable|exists:empleados,id',
            'clienteId'      => 'required|exists:clientes,id',
            'vehiculoId'     => 'required|exists:vehiculos,id',
            'fecha'          => 'required|date',
            'hora'           => 'required|date_format:H:i',
            'estado'         => ['required', Rule::in(['pendiente', 'confirmada', 'cancelada'])],
            'tipoDeAtencion' => ['required', Rule::in(['Por orden de llegada', 'Por cita agendada'])],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('fecha') || $validator->errors()->has('hora') ||
                $validator->errors()->has('vehiculoId')) {
                return;
            }

            $fecha = $this->input('fecha');
            $hora = $this->input('hora');
            $estado = $this->input('estado');
            $agendaId = $this->route('agenda');

            if ($estado === 'cancelada') {
                return;
            }

            if (!HorarioLavadero::permite($fecha, $hora)) {
                $validator->errors()->add('hora', 'La hora está fuera del horario de atención del lavadero para ese día.');
            }

            $vehiculoOcupado = DB::table('agendas')
                ->where('vehiculoId', $this->input('vehiculoId'))
                ->where('fecha', $fecha)
                ->whereTime('hora', '=', $hora)
                ->where('estado', '!=', 'cancelada')
                ->where('id', '!=', $agendaId)
                ->exists();

            if ($vehiculoOcupado) {
                $validator->errors()->add('vehiculoId', 'Ese vehículo ya tiene una cita en esa fecha y hora.');
            }

            $empleadoId = $this->input('empleadoId');
            if (!$empleadoId) {
                return;
            }

            $empleadoActivo = DB::table('empleados')->where('id', $empleadoId)
                ->where('estado', true)->exists();
            if (!$empleadoActivo) {
                $validator->errors()->add('empleadoId', 'Solo se puede asignar una cita a un empleado activo.');
                return;
            }

            $dias = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'];
            $dia = $dias[Carbon::parse($fecha)->dayOfWeekIso];
            $horaCompleta = $hora . ':00';
            $tieneTurno = DB::table('turnos')->where('empleado_id', $empleadoId)
                ->where('dia', $dia)
                ->where('horaInicio', '<=', $horaCompleta)
                ->where('horaFin', '>', $horaCompleta)
                ->exists();

            if (!$tieneTurno) {
                $validator->errors()->add('empleadoId', 'El empleado no tiene un turno que cubra ese día y esa hora.');
            }

            $empleadoOcupado = DB::table('agendas')->where('empleadoId', $empleadoId)
                ->where('fecha', $fecha)
                ->whereTime('hora', '=', $hora)
                ->where('estado', '!=', 'cancelada')
                ->where('id', '!=', $agendaId)
                ->exists();

            if ($empleadoOcupado) {
                $validator->errors()->add('empleadoId', 'Ese empleado ya tiene una cita en esa fecha y hora.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'empleadoId.exists'          => 'El empleado seleccionado no existe',
            'clienteId.required'         => 'El cliente es obligatorio',
            'clienteId.exists'           => 'El cliente seleccionado no existe',
            'vehiculoId.required'        => 'El vehículo es obligatorio',
            'vehiculoId.exists'          => 'El vehículo seleccionado no existe',
            'fecha.required'             => 'La fecha es obligatoria',
            'fecha.date'                 => 'La fecha no es válida',
            'hora.required'              => 'La hora es obligatoria',
            'hora.date_format'           => 'La hora no es válida',
            'estado.required'            => 'El estado es obligatorio',
            'estado.in'                  => 'El estado seleccionado no es válido',
            'tipoDeAtencion.required'    => 'El tipo de atención es obligatorio',
            'tipoDeAtencion.in'          => 'El tipo de atención seleccionado no es válido',
        ];
    }
}
