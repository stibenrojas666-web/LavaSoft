<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrecioServicio extends Model
{
    use HasFactory;

    protected $table = 'precio_servicios';

    protected $fillable = [
        'servicio_id',
        'tipo_vehiculo_id',
        'precio',
        'modalidad',
        'duracionMinutos',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracionMinutos' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }
}