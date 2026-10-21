<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'fecha'          => 'required|date',
            'hora'           => 'required|date_format:H:i',
            'estado'         => ['required', Rule::in(['pendiente', 'confirmada', 'cancelada', 'terminada'])],
            'tipoDeAtencion' => ['required', Rule::in(['Por orden de llegada', 'Por cita agendada'])],
            'vehiculos' => ['required', 'array', 'min:1'],
            'vehiculos.*.vehiculoId' => ['required', 'integer', 'exists:vehiculos,id'],
            'vehiculos.*.servicios' => ['required', 'array', 'min:1'],
            'vehiculos.*.servicios.*.servicioId' => ['required', 'integer', 'exists:servicios,id'],
            'vehiculos.*.servicios.*.precioAcordado' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'vehiculos.*.servicios.*.precioAcordadoActivo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'empleadoId.exists'          => 'El empleado seleccionado no existe',
            'clienteId.required'         => 'El cliente es obligatorio',
            'clienteId.exists'           => 'El cliente seleccionado no existe',
            'fecha.required'             => 'La fecha es obligatoria',
            'fecha.date'                 => 'La fecha no es válida',
            'hora.required'              => 'La hora es obligatoria',
            'hora.date_format'           => 'La hora no es válida',
            'estado.required'            => 'El estado es obligatorio',
            'estado.in'                  => 'El estado seleccionado no es válido',
            'tipoDeAtencion.required'    => 'El tipo de atención es obligatorio',
            'tipoDeAtencion.in'          => 'El tipo de atención seleccionado no es válido',
            'vehiculos.required' => 'Agrega por lo menos un vehículo a la cita.',
            'vehiculos.min' => 'Agrega por lo menos un vehículo a la cita.',
            'vehiculos.*.vehiculoId.required' => 'Selecciona el vehículo de este bloque.',
            'vehiculos.*.vehiculoId.exists' => 'El vehículo seleccionado no existe.',
            'vehiculos.*.servicios.required' => 'Agrega por lo menos un servicio a cada vehículo.',
            'vehiculos.*.servicios.min' => 'Agrega por lo menos un servicio a cada vehículo.',
            'vehiculos.*.servicios.*.servicioId.required' => 'Selecciona un servicio para este vehículo.',
            'vehiculos.*.servicios.*.servicioId.exists' => 'El servicio seleccionado no existe.',
            'vehiculos.*.servicios.*.precioAcordado.numeric' => 'El precio acordado debe ser un número.',
            'vehiculos.*.servicios.*.precioAcordado.min' => 'El precio acordado debe ser mayor que cero.',
            'vehiculos.*.servicios.*.precioAcordado.max' => 'El precio acordado es demasiado alto.',
            'vehiculos.*.servicios.*.precioAcordadoActivo.boolean' => 'La opción de precio acordado no es válida.',
        ];
    }
}
