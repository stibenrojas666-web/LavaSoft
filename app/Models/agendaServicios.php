<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class agendaServicios extends Model
{
    protected $table = 'agenda_servicios';

    protected $fillable = [
        'agendaId',
        'servicioId',
        'Precio',
    ];

    public function agenda()
    {
        return $this->belongsTo(agenda::class, 'agendaId');
    }

    public function servicio()
    {
        return $this->belongsTo(servicio::class, 'servicioId');
    }
}