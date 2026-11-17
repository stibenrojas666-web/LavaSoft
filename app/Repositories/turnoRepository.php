<?php

namespace App\Repositories;

use App\Repositories\Contracts\TurnoRepositoryInterface;

use App\Models\Turno;
use App\Support\TextoBusqueda;

class TurnoRepository implements TurnoRepositoryInterface {
    public function tieneCruce(int $empleadoId, string $dia, string $horaInicio, string $horaFin, ?int $turnoId = null): bool
    {
        return Turno::where('empleado_id', $empleadoId)
            ->where('dia', $dia)
            ->where('horaInicio', '<', $horaFin)
            ->where('horaFin', '>', $horaInicio)
            ->when($turnoId, fn ($query) => $query->where('id', '!=', $turnoId))
            ->exists();
    }

    public function listarTodo()
    {
        return Turno::query()
            ->select(['id', 'empleado_id', 'dia', 'jornada', 'horaInicio', 'horaFin'])
            ->with('empleado:id,nombre,apellido,identificacion')
            ->orderBy('dia')
            ->orderBy('horaInicio')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(Turno::query()
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
            ->paginate(10)
            ->withQueryString();
    }

    public function guardar(array $datos)
    {
        return Turno::create($datos);
    }

    public function eliminar(int $id)
    {
        Turno::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return Turno::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $turno = Turno::findOrFail($id);
        $turno->update($datos);
        return $turno;
    }
}
