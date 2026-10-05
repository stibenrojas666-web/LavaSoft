<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
  protected $table = 'clientes';
  protected $fillable = [
    'nombreCliente',
    'apellidoCliente',
    'telefonoCliente',
    'emailCliente',
  ];
    
      public function vehiculo()
    {
        return $this->hasMany(clientes::class); /**, 'clienteID' */
    } 

    PUblic function agendas()
    {
        return $this->hasMany(agenda::class);
    }
}
