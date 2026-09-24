<?php

namespace App\Repositories;

use App\Models\empleado;

class empleadoRepository
{
    public function listarTodo()
    {
        return empleado::all();
    }

    public function guardar(array $datos)
    {
        return empleado::create($datos);
    }

    public function eliminar(int $id)
    {
        empleado::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return empleado::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $empleado = empleado::findOrFail($id);
        $empleado->update($datos);
        return $empleado;
    }
}