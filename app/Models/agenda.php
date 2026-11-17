<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agendas';

    protected $fillable = [
        'empleadoId',
        'clienteId',
        'vehiculoId',
        'fecha',
        'hora',
        'estado',
        'tipoDeAtencion',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleadoId');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clienteId');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'agendaId');
    }

    public function agendaServicios()
    {
        return $this->hasMany(AgendaServicios::class, 'agendaId')->with(['vehiculo.tipoVehiculo', 'servicio']);
    }

    public function vehiculos()
    {
        return $this->belongsToMany(Vehiculo::class, 'agenda_servicios', 'agendaId', 'vehiculoId')
            ->distinct();
    }

}
