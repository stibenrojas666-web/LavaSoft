<?php

namespace App\Repositories;

use App\Models\tipoVehiculo;

class tipoVehiculoRepository
{
    public function listarTodo()
    {
        return tipoVehiculo::all();
    }

    public function guardar(array $datos)
    {
        return tipoVehiculo::create($datos);
    }

    public function eliminar(int $id)
    {
        tipoVehiculo::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return tipoVehiculo::findOrfail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $tipovehiculo = tipoVehiculo::findOrFail($id);
        $tipovehiculo->update($datos);
        return $tipovehiculo;
    }
}