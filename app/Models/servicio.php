<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{
    use HasFactory;
    protected $table = 'servicios';
    protected $fillable = [
        'nombre',
        'descripcion',
        'duracionMinutos',
        'estado',
    ];

    public function agendaServicios()
    {
        return $this->hasMany(AgendaServicios::class, 'servicioId');
    }

    public function agendas()
    {
        return $this->belongsToMany(Agenda::class, 'agenda_servicios', 'servicioId', 'agendaId')
            ->withPivot('Precio')
            ->withTimestamps();
    }

    public function precios()
    {
        return $this->hasMany(PrecioServicio::class, 'servicio_id');
    }
}
