<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\clientes;

class vehiculo extends Model
{
    protected $table = 'vehiculos';
    protected $fillable = [
        'clienteID',
        'tipoVehiculoId',
        'placa',
        'modelo',
        'color',
        'color',
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'clienteID');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(clientes::class, 'tipoVehiculoId');
    }
}
