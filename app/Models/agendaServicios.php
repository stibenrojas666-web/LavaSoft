<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaServicios extends Model
{
    protected $table = 'agenda_servicios';

    protected $fillable = [
        'agendaId',
        'vehiculoId',
        'servicioId',
        'Precio',
        'duracionMinutos',
    ];

    protected $casts = [
        'duracionMinutos' => 'integer',
        'Precio' => 'decimal:2',
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agendaId');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicioId');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculoId');
    }
}