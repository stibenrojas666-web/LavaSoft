<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class agenda extends Model
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
        return $this->belongsTo(empleado::class, 'empleadoId');
    }

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'clienteId');
    }

    public function pago()
    {
        return $this->hasOne(pago::class, 'agendaId');
    }

    public function agendaServicios()
    {
        return $this->hasMany(agendaServicios::class, 'agendaId')->with(['vehiculo.tipoVehiculo', 'servicio']);
    }

    public function vehiculos()
    {
        return $this->belongsToMany(vehiculo::class, 'agenda_servicios', 'agendaId', 'vehiculoId')
            ->distinct();
    }

    /** Total vigente de los precios guardados para los servicios de esta cita. */
    public function totalServicios(): float
    {
        return (float) $this->agendaServicios()->sum('Precio');
    }

}
