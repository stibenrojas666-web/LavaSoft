<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionLavadero extends Model
{
    protected $table = 'configuracion_lavadero';

    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'logo_path',
        'mostrar_logo',
    ];

    protected $casts = [
        'mostrar_logo' => 'boolean',
    ];

    public function getMostrarLogoAttribute($value): bool
    {
        return $value === null ? true : (bool) $value;
    }
}
