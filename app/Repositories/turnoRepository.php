<?php

namespace App\Repositories;

use App\Models\turno;

class turnoRepository
{
    public function tieneCruce(int $empleadoId, string $dia, string $horaInicio, string $horaFin, ?int $turnoId = null): bool
    {
        return turno::where('empleado_id', $empleadoId)
            ->where('dia', $dia)
            ->where('horaInicio', '<', $horaFin)
            ->where('horaFin', '>', $horaInicio)
            ->when($turnoId, fn ($query) => $query->where('id', '!=', $turnoId))
            ->exists();
    }

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
