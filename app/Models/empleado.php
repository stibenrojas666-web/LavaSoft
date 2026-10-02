<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = [
        'identificacion',
        'telefono',
        'rh',
        'eps',
        'estado',
    ];
}