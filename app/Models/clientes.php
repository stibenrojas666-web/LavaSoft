<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class clientes extends Model
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
      return $this->hasMany(vehiculo::class, 'clienteID');
    } 

    PUblic function agendas()
    {
        return $this->hasMany(agenda::class);
    }
}
