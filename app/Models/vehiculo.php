<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'clienteID',
        'tipoVehiculoId',
        'placa',
        'modelo',
        'color',
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'clienteID');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(tipoVehiculo::class, 'tipoVehiculoId');
    }

    public function agendas()
    {
        return $this->hasMany(agenda::class);
    }
}