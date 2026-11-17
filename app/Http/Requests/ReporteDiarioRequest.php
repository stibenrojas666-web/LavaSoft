<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReporteDiarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('ver dashboard') ?? false;
    }

    public function rules(): array
    {
        return ['fecha' => ['nullable', 'date']];
    }

    public function messages(): array
    {
        return ['fecha.date' => 'La fecha del reporte no es válida.'];
    }
}
