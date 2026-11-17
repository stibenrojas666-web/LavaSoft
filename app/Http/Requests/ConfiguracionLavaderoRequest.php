<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionLavaderoRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $normalizarHora = static function ($valor): mixed {
            if (! is_string($valor)) {
                return $valor;
            }

            $valor = trim($valor);
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $valor)) {
                return substr($valor, 0, 5);
            }

            return $valor;
        };

        $this->merge([
            'apertura_regular' => $normalizarHora($this->input('apertura_regular')),
            'cierre_regular' => $normalizarHora($this->input('cierre_regular')),
            'apertura_domingo_festivo' => $normalizarHora($this->input('apertura_domingo_festivo')),
            'cierre_domingo_festivo' => $normalizarHora($this->input('cierre_domingo_festivo')),
        ]);
    }

    public function authorize(): bool
    {
        return $this->user()?->can(config('seguridad.permiso_admin')) ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'nit' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'apertura_regular' => ['required', 'date_format:H:i', 'before:cierre_regular'],
            'cierre_regular' => ['required', 'date_format:H:i', 'after:apertura_regular'],
            'apertura_domingo_festivo' => ['required', 'date_format:H:i', 'before:cierre_domingo_festivo'],
            'cierre_domingo_festivo' => ['required', 'date_format:H:i', 'after:apertura_domingo_festivo'],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png,webp,gif,svg,bmp,ico', 'max:10240'],
            'mostrar_logo' => ['nullable', 'boolean'],
            'eliminar_logo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del lavadero es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'nit.max' => 'El NIT no puede superar los 30 caracteres.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
            'telefono.max' => 'El teléfono no puede superar los 30 caracteres.',
            'apertura_regular.required' => 'Indica la hora de apertura entre semana y los sábados.',
            'apertura_regular.date_format' => 'La hora de apertura debe tener formato HH:MM.',
            'apertura_regular.before' => 'La apertura debe ser antes del cierre regular.',
            'cierre_regular.required' => 'Indica la hora de cierre entre semana y los sábados.',
            'cierre_regular.date_format' => 'La hora de cierre debe tener formato HH:MM.',
            'cierre_regular.after' => 'El cierre debe ser después de la apertura regular.',
            'apertura_domingo_festivo.required' => 'Indica la hora de apertura de domingos y festivos.',
            'apertura_domingo_festivo.date_format' => 'La hora debe tener formato HH:MM.',
            'apertura_domingo_festivo.before' => 'La apertura debe ser antes del cierre de domingos y festivos.',
            'cierre_domingo_festivo.required' => 'Indica la hora de cierre de domingos y festivos.',
            'cierre_domingo_festivo.date_format' => 'La hora debe tener formato HH:MM.',
            'cierre_domingo_festivo.after' => 'El cierre debe ser después de la apertura de domingos y festivos.',
            'logo.mimes' => 'El logo debe estar en formato JPG, PNG, WEBP, GIF, SVG, BMP o ICO.',
            'logo.max' => 'El logo no puede superar los 10 MB.',
            'mostrar_logo.boolean' => 'La opción de mostrar el logo no es válida.',
            'eliminar_logo.boolean' => 'La opción de eliminar el logo no es válida.',
        ];
    }
}
