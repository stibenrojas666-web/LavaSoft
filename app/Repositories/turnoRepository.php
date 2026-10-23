<?php

namespace App\Repositories;

use App\Models\turno;
use App\Support\TextoBusqueda;

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
        return turno::query()
            ->select(['id', 'empleado_id', 'dia', 'jornada', 'horaInicio', 'horaFin'])
            ->with('empleado:id,nombre,apellido,identificacion')
            ->orderBy('dia')
            ->orderBy('horaInicio')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(turno::query()
            ->select(['id', 'empleado_id', 'dia', 'jornada', 'horaInicio', 'horaFin'])
            ->with('empleado:id,nombre,apellido,identificacion'), $texto, function ($coincidencias, $termino) {
                $coincidencias->where('dia', 'like', $termino)
                    ->orWhere('jornada', 'like', $termino)
                    ->orWhere('horaInicio', 'like', $termino)
                    ->orWhere('horaFin', 'like', $termino)
                    ->orWhereHas('empleado', fn ($empleado) => $empleado->where('nombre', 'like', $termino)
                        ->orWhere('apellido', 'like', $termino)
                        ->orWhere('identificacion', 'like', $termino));
            })
            ->when(($filtros['dia'] ?? '') !== '', fn ($query) => $query->where('dia', $filtros['dia']))
            ->when(($filtros['jornada'] ?? '') !== '', fn ($query) => $query->where('jornada', $filtros['jornada']))
            ->orderBy('dia')
            ->orderBy('horaInicio')
            ->paginate(12)
            ->withQueryString();
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
