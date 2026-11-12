<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombreCliente',
        'apellidoCliente',
        'telefonoCliente',
        'emailCliente',
    ];

    public function vehiculo()
    {
        return $this->hasMany(Vehiculo::class, 'clienteID');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'clienteId');
    }
}