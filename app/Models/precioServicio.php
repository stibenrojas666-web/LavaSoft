<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class precioServicio extends Model
{
    use HasFactory;
    protected $table = 'precio_servicios';
    protected $fillable = [
        'servicio_id',
        'tipo_vehiculo_id',
        'precio',
        'modalidad',
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


