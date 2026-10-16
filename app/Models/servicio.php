<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class servicio extends Model
{
    use HasFactory;
    protected $table = 'servicios';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function agendaServicios()
    {
        return $this->hasMany(agendaServicios::class, 'servicioId');
    }

    public function agendas()
    {
        return $this->belongsToMany(agenda::class, 'agenda_servicios', 'servicioId', 'agendaId')
            ->withPivot('Precio')
            ->withTimestamps();
    }

    public function precios()
    {
        return $this->hasMany(precioServicio::class, 'servicio_id');
    }
}
