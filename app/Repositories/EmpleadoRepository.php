<?php

namespace App\Repositories;

use App\Models\Empleado;

class EmpleadoRepository
{
    public function listarTodo()
    {
        return Empleado::all();
    }

    public function guardar(array $datos)
    {
        return Empleado::create($datos);
    }

    public function eliminar(int $id)
    {
        Empleado::destroy($id);
    }

    public function buscarporId(int $id)
    {
        return Empleado::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($datos);
        return $empleado;
    }
}