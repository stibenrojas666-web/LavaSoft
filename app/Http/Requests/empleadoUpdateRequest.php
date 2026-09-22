<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class empleadoUpdateRequest extends FormRequest
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
            'identificacion' => 'required|string|max:20',
            'telefono' => 'required|string|max:15',
            'rh' => 'required|string|max:3',
            'eps' => 'required|string|max:50',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'identificacion.required' => 'El campo identificacion es obligatorio.',
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'rh.required' => 'El campo RH es obligatorio.',
            'eps.required' => 'El campo EPS es obligatorio.'
        ];
    }
}
