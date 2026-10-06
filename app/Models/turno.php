<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class turno extends Model
{
    protected $table = 'turnos';
    protected $fillable =[
        'empleado_id','dia','jornada',
    ];
    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'empleado_id');
    }
}
