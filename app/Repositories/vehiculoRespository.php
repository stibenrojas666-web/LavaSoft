<?php

namespace App\Repositories;
use App\Models\vehiculo;

class VehiculoRespository{
    public function getAll(){
        return vehiculo::all();
    }

    public function keep(array $datos){
    return vehiculo::create($datos);
    }

    public function finById(int $id){
        return vehiculo::findOrFail($id);
    }

    public function update (int $id, array $datos){
        $vehiculo= vehiculo::findOrFail($id);
        $vehiculo->update($datos);
        return $vehiculo; 
    }

    public function delete (int $id){
        return vehiculo::destroy($id);
    }
}