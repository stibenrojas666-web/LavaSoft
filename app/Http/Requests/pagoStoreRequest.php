<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PagoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agendaId'        => 'required|exists:agendas,id',
            'MetodoPago'      => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'RequerirFactura' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'agendaId.required'        => 'La cita es obligatoria',
            'agendaId.exists'          => 'La cita seleccionada no existe',
            'MetodoPago.required'      => 'El método de pago es obligatorio',
            'MetodoPago.in'            => 'El método de pago seleccionado no es válido',
            'RequerirFactura.required' => 'Indique si requiere factura',
            'RequerirFactura.boolean'  => 'El valor de factura no es válido',
        ];
    }
}
