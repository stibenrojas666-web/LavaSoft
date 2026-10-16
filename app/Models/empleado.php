<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class empleado extends Model
{
    use HasFactory;
    protected $table = 'empleados';
    protected $fillable = [
        'nombre','apellido',
        'identificacion',
        'telefono',
        'rh',
        'eps',
        'estado',
    ];

    public function agendas()
    {
        return $this->hasMany(agenda::class);
    }

}
