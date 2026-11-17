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
        'apertura_regular',
        'cierre_regular',
        'apertura_domingo_festivo',
        'cierre_domingo_festivo',
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
