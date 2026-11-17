<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
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
        return $this->belongsTo(Cliente::class, 'clienteID');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipoVehiculoId');
    }

    public function agendas()
    {
        return $this->belongsToMany(Agenda::class, 'agenda_servicios', 'vehiculoId', 'agendaId')
            ->distinct();
    }

    public function agendaServicios()
    {
        return $this->hasMany(AgendaServicios::class, 'vehiculoId');
    }
}
