<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'agendaId',
        'Monto',
        'MetodoPago',
        'FechaPago',
        'RequerirFactura',
    ];

    protected $casts = [
        'FechaPago'       => 'datetime',
        'RequerirFactura' => 'boolean',
    ];

    public function agenda()
    {
        return $this->belongsTo(agenda::class, 'agendaId');
    }
}