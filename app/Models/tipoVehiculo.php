<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tipoVehiculo extends Model
{
    use HasFactory;
    protected $table = 'tipo_vehiculos';

    protected $fillable = [
        'nombre',
    ];

    public function vehiculos()
    {
        return $this->hasMany(vehiculo::class, 'tipoVehiculoId');
    }
}
