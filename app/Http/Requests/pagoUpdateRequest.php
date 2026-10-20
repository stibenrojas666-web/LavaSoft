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
            'MetodoPago'      => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'RequerirFactura' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'MetodoPago.required'      => 'El método de pago es obligatorio',
            'MetodoPago.in'            => 'El método de pago seleccionado no es válido',
            'RequerirFactura.required' => 'Indique si requiere factura',
            'RequerirFactura.boolean'  => 'El valor de factura no es válido',
        ];
    }
}
