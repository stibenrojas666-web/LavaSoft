<?php

namespace App\Repositories;

use App\Models\clientes;
use App\Models\agenda;
use App\Models\vehiculo;

class clienteRepository {
public function listarTodo(){
    return clientes::all();
}

public function guardar(array $datos){
    return clientes::create($datos);
    }
    
 public function eliminar(int $id){
    return clientes::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return vehiculo::where('clienteID', $id)->exists()
            || agenda::where('clienteId', $id)->exists();
    }

    public function buscarPorId(int $id){
        return clientes::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $cliente= clientes::findOrFail($id);
        $cliente->update($datos);
        return $cliente;    
    }
 }
