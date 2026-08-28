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
    
}
