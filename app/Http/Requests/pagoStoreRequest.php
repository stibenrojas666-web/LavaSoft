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
            'MetodoPago'      => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'RequerirFactura' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'MetodoPago.required'      => 'El método de pago es obligatorio',
            'MetodoPago.in'            => 'El método de pago seleccionado no es válido',
            'RequerirFactura.boolean'  => 'El valor de factura no es válido',
        ];
    }
}
