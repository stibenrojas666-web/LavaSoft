<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = 'turnos';
    protected $fillable =[
        'empleado_id','dia','jornada','horaInicio','horaFin'
    ];
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
