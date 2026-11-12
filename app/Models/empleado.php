<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'nombre',
        'apellido',
        'identificacion',
        'telefono',
        'rh',
        'eps',
        'estado',
        'porcentajeComision',
    ];

    protected $casts = [
        'porcentajeComision' => 'decimal:2',
    ];

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'empleadoId');
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'empleado_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'empleadoId');
    }
}