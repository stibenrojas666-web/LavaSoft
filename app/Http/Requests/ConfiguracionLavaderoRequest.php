<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionLavaderoRequest extends FormRequest
{
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
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'mostrar_logo' => ['nullable', 'boolean'],
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
            'logo.image' => 'El archivo debe ser una imagen.',
            'logo.mimes' => 'El logo debe estar en formato JPG, PNG o WEBP.',
            'logo.max' => 'El logo no puede superar los 2 MB.',
            'mostrar_logo.boolean' => 'La opción de mostrar el logo no es válida.',
        ];
    }
}
