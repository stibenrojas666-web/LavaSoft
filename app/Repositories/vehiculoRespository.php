<?php

namespace App\Repositories;
use App\Models\vehiculo;
use App\Models\agenda;

class VehiculoRespository{
    public function getAll(){
        return vehiculo::with(['cliente', 'tipoVehiculo'])->get();
    }

    public function keep(array $datos){
    return vehiculo::create($datos);
    }

    public function finById(int $id){
        return vehiculo::with(['cliente', 'tipoVehiculo'])->findOrFail($id);
    }

    public function update (int $id, array $datos){
        $vehiculo= vehiculo::findOrFail($id);
        $vehiculo->update($datos);
        return $vehiculo; 
    }

    public function delete (int $id){
        return vehiculo::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return agenda::where('vehiculoId', $id)->exists();
    }
}
