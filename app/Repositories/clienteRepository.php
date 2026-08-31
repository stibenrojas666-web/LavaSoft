<?php

namespace App\Repositories;

use App\Models\clientes;

class clienteRepository {
public function listarTodo(){
    return clientes::all();
}

public function guardar(array $datos){
    return clientes::create($datos);
    }
    
 public function eliminar(int $id){
    clientes::destroy($id);
    }

    public function buscarPorId(int $id){
        return clientes::find($id);
    }

 }
