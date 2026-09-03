<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = [
        'telefono',
        'rh',
        'eps',
    ];
}
