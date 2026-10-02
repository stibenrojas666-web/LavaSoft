<?php

namespace App\Repositories;

use App\Models\precioServicio;

class precioServicioRepository
{
    public function listarTodo()
    {
        return precioServicio::all();
    }

    public function guardar(array $datos)
    {
        return precioServicio::create($datos);
    }

    public function eliminar(int $id)
    {
        precioServicio::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return precioServicio::findOrfail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $precioServicio = precioServicio::findOrFail($id);
        $precioServicio->update($datos);
        return $precioServicio;
    }
}