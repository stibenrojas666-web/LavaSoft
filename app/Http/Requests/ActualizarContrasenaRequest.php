<?php

namespace App\Http\Requests;

use App\Services\Seguridad\RecuperacionContrasenaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ActualizarContrasenaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $sesion = $this->session();
        $vigente = app(RecuperacionContrasenaService::class)->verificacionVigente(
            (bool) $sesion->get('recuperacion_verificada'),
            $sesion->get('recuperacion_verificada_en'),
            $sesion->has('recuperacion_email')
        );

        if (! $vigente) {
            return [];
        }

        return ['password' => ['required', 'confirmed', Password::min(8)]];
    }
}
