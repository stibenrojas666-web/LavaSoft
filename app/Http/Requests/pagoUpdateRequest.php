<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PagoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agendaId'        => 'required|exists:agendas,id',
            'Monto'           => 'required|numeric|min:0.01|max:99999999.99',
            'MetodoPago'      => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'RequerirFactura' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'agendaId.required'        => 'La cita es obligatoria',
            'agendaId.exists'          => 'La cita seleccionada no existe',
            'Monto.required'           => 'El monto es obligatorio',
            'Monto.numeric'            => 'El monto debe ser un número',
            'Monto.min'                => 'El monto debe ser mayor a 0',
            'Monto.max'                => 'El monto es demasiado alto',
            'MetodoPago.required'      => 'El método de pago es obligatorio',
            'MetodoPago.in'            => 'El método de pago seleccionado no es válido',
            'RequerirFactura.required' => 'Indique si requiere factura',
            'RequerirFactura.boolean'  => 'El valor de factura no es válido',
        ];
    }
}