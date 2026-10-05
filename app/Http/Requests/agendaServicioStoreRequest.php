<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaServicioStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agendaId'   => 'required|exists:agendas,id',
            'servicioId' => [
                'required',
                'exists:servicios,id',
                // El mismo servicio no puede repetirse en la misma cita
                Rule::unique('agenda_servicios', 'servicioId')->where('agendaId', $this->agendaId),
            ],
            'Precio'     => 'required|numeric|min:0|max:99999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'agendaId.required'   => 'La cita es obligatoria',
            'agendaId.exists'     => 'La cita seleccionada no existe',
            'servicioId.required' => 'El servicio es obligatorio',
            'servicioId.exists'   => 'El servicio seleccionado no existe',
            'servicioId.unique'   => 'Este servicio ya está agregado a la cita',
            'Precio.required'     => 'El precio es obligatorio',
            'Precio.numeric'      => 'El precio debe ser un número',
            'Precio.min'          => 'El precio no puede ser negativo',
            'Precio.max'          => 'El precio es demasiado alto',
        ];
    }
}