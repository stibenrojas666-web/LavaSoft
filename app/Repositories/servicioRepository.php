<?php

namespace App\Repositories;

use App\Models\servicio;

class servicioRepository
{
    public function listarTodo()
    {
        return servicio::all();
    }

    public function guardar(array $datos)
    {
        return servicio::create($datos);
    }

    public function eliminar(int $id)
    {
        servicio::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return servicio::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $servicio = servicio::findOrFail($id);
        $servicio->update($datos);
        return $servicio;
    }
}