<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class agenda extends Model
{
    protected $table = 'agendas';

    protected $fillable = [
        'empleadoId',
        'clienteId',
        'vehiculoId',
        'fecha',
        'hora',
        'estado',
        'tipoDeAtencion',
    ];

    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'empleadoId');
    }

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'clienteId');
    }

    public function vehiculo()
    {
        return $this->belongsTo(vehiculo::class, 'vehiculoId');
    }
}
