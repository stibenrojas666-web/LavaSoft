<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificarCodigoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        if (! $this->session()->has('recuperacion_email')) {
            return [];
        }

        return ['codigo' => ['required', 'digits:6']];
    }
}
