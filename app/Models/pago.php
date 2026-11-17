<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'agendaId',
        'empleadoId',
        'Monto',
        'MetodoPago',
        'FechaPago',
        'RequerirFactura',
        'porcentajeComision',
        'valorComision',
    ];

    protected $casts = [
        'FechaPago' => 'datetime',
        'RequerirFactura' => 'boolean',
        'porcentajeComision' => 'decimal:2',
        'valorComision' => 'decimal:2',
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agendaId');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleadoId');
    }
}