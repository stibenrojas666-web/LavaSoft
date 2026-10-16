<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaStoreRequest extends FormRequest
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
            'fecha'          => 'required|date|after_or_equal:today',
            'hora'           => 'required|date_format:H:i',
            'estado'         => ['required', Rule::in(['pendiente', 'confirmada', 'cancelada'])],
            'tipoDeAtencion' => ['required', Rule::in(['Por orden de llegada', 'Por cita agendada'])],
            'servicios'   => ['required', 'array', 'min:1'],
            'servicios.*' => ['required', 'integer', 'distinct', 'exists:servicios,id'],
            'precios_acordados' => ['nullable', 'array'],
            'precios_acordados.*' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
        ];
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
            'fecha.after_or_equal'       => 'La fecha no puede ser anterior a hoy',
            'hora.required'              => 'La hora es obligatoria',
            'hora.date_format'           => 'La hora no es válida',
            'estado.required'            => 'El estado es obligatorio',
            'estado.in'                  => 'El estado seleccionado no es válido',
            'tipoDeAtencion.required'    => 'El tipo de atención es obligatorio',
            'tipoDeAtencion.in'          => 'El tipo de atención seleccionado no es válido',
            'servicios.required' => 'Selecciona al menos un servicio para la cita.',
'servicios.min' => 'Selecciona al menos un servicio para la cita.',
'servicios.*.distinct' => 'No puedes seleccionar el mismo servicio más de una vez.',
'servicios.*.exists' => 'Uno de los servicios seleccionados no existe.',
            'precios_acordados.*.numeric' => 'El precio acordado debe ser un número.',
            'precios_acordados.*.min' => 'El precio acordado debe ser mayor que cero.',
            'precios_acordados.*.max' => 'El precio acordado es demasiado alto.',
        ];
    }
}
