<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoVehiculo extends Model
{
    protected $table = 'tipo_vehiculos';
    protected $fillable = [
        'nombre', 
    ];
}
