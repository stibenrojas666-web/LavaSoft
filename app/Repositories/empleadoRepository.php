<?php

namespace App\Repositories;

use App\Models\empleado;
use App\Models\agenda;
use App\Models\turno;

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
        return empleado::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return agenda::where('empleadoId', $id)->exists()
            || turno::where('empleado_id', $id)->exists();
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
