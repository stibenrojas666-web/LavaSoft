<?php

namespace App\Repositories;

use App\Models\turno;;

class turnoRepository
{
    public function listarTodo()
    {
        return turno::with('empleado')->get();
    }

    public function guardar(array $datos)
    {
        return turno::create($datos);
    }

    public function eliminar(int $id)
    {
        turno::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return turno::findOrfail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $turno = turno::findOrFail($id);
        $turno->update($datos);
        return $turno;
    }
}