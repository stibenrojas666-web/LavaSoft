<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agendaId' => ['nullable', 'integer', 'exists:agendas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'agendaId.integer' => 'La cita seleccionada no es válida.',
            'agendaId.exists' => 'La cita seleccionada no existe.',
        ];
    }
}
