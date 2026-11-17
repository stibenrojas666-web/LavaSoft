<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombreCliente',
        'apellidoCliente',
        'telefonoCliente',
        'emailCliente',
        'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function vehiculo()
    {
        return $this->hasMany(Vehiculo::class, 'clienteID');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'clienteId');
    }
}
