<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportePeriodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('ver dashboard') ?? false;
    }

    public function rules(): array
    {
        $hasta = ['nullable', 'date'];
        if ($this->filled('desde')) {
            $hasta[] = 'after_or_equal:desde';
        }

        return [
            'desde' => ['nullable', 'date'],
            'hasta' => $hasta,
        ];
    }

    public function messages(): array
    {
        return [
            'desde.date' => 'La fecha inicial no es válida.',
            'hasta.date' => 'La fecha final no es válida.',
            'hasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la inicial.',
        ];
    }
}
