<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaServicioUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agendaId'   => 'required|exists:agendas,id',
            'vehiculoId' => 'required|exists:vehiculos,id',
            'Precio' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'servicioId' => [
                'required',
                'exists:servicios,id',
                Rule::unique('agenda_servicios', 'servicioId')
                    ->where('agendaId', $this->agendaId)
                    ->where('vehiculoId', $this->vehiculoId)
                    ->ignore($this->route('agendaServicio')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'agendaId.required'   => 'La cita es obligatoria',
            'agendaId.exists'     => 'La cita seleccionada no existe',
            'vehiculoId.required' => 'El vehículo es obligatorio.',
            'vehiculoId.exists' => 'El vehículo seleccionado no existe.',
            'servicioId.required' => 'El servicio es obligatorio',
            'servicioId.exists'   => 'El servicio seleccionado no existe',
            'servicioId.unique'   => 'Este servicio ya está agregado para ese vehículo en la cita.',
            'Precio.numeric' => 'El precio acordado debe ser un número.',
            'Precio.min' => 'El precio acordado debe ser mayor que cero.',
            'Precio.max' => 'El precio acordado es demasiado alto.',
        ];
    }
}
