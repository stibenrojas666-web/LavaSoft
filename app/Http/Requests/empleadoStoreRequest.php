<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class empleadoStoreRequest extends FormRequest
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
            'telefono' => 'required|string|max:15',
            'rh' => 'required|string|max:3',
            'eps' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'rh.required' => 'El campo RH es obligatorio.',
            'eps.required' => 'El campo EPS es obligatorio.',
        ];
    }
}
