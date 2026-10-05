<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class precioServicio extends Model
{
    protected $table = 'precio_servicios';
    protected $fillable = [
        'servicio_id',
        'tipo_vehiculo_id',
        'precio',
    ];

    public function servicio()
    {
        return $this->belongsTo(servicio::class, 'servicio_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(tipoVehiculo::class, 'tipo_vehiculo_id');
    }
}


