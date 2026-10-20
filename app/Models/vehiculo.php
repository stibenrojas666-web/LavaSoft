<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class vehiculo extends Model
{
    use HasFactory;
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
