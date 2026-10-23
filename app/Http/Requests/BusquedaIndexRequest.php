<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusquedaIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $fechaFinal = ['nullable', 'date'];
        if ($this->filled('desde')) {
            $fechaFinal[] = 'after_or_equal:desde';
        }

        return [
            'buscar' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'in:activo,inactivo,pendiente,confirmada,terminada,cancelada'],
            'dia' => ['nullable', 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'jornada' => ['nullable', 'in:mañana,tarde,noche'],
            'tipoVehiculoId' => ['nullable', 'integer', 'exists:tipo_vehiculos,id'],
            'modalidad' => ['nullable', 'in:fijo,desde,convenir'],
            'metodo' => ['nullable', 'in:Efectivo,Tarjeta,Transferencia'],
            'desde' => ['nullable', 'date'],
            'hasta' => $fechaFinal,
            'mes' => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'vista' => ['nullable', 'in:lista,calendario'],
            'fecha' => ['nullable', 'date'],
            'orden' => ['nullable', 'in:fecha_asc,fecha_desc,hora_asc,hora_desc'],
        ];
    }

    public function messages(): array
    {
        return [
            'buscar.string' => 'La búsqueda debe ser texto.',
            'buscar.max' => 'La búsqueda no puede superar los 100 caracteres.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'dia.in' => 'El día seleccionado no es válido.',
            'jornada.in' => 'La jornada seleccionada no es válida.',
            'tipoVehiculoId.integer' => 'El tipo de vehículo seleccionado no es válido.',
            'tipoVehiculoId.exists' => 'El tipo de vehículo seleccionado ya no existe.',
            'modalidad.in' => 'La modalidad seleccionada no es válida.',
            'metodo.in' => 'El método de pago seleccionado no es válido.',
            'desde.date' => 'La fecha inicial no es válida.',
            'hasta.date' => 'La fecha final no es válida.',
            'hasta.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
            'mes.regex' => 'El mes seleccionado no tiene un formato válido.',
            'vista.in' => 'La vista seleccionada no es válida.',
            'orden.in' => 'El orden seleccionado no es válido.',
            'fecha.date' => 'La fecha seleccionada no es válida.',
        ];
    }
}
