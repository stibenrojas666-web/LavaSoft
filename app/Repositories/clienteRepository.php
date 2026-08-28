<?php

namespace App\Repositories;

use App\Models\clientes;

class clienteRepository {
public function listarTodo(){
    return clientes::all();
}
}