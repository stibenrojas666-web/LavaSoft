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

    public function disponiblesEnHorario(string $fecha, string $hora, string $dia, ?int $agendaId = null)
    {
        $horaCompleta = $hora . ':00';

        return empleado::query()
            ->where('estado', true)
            ->whereHas('turnos', function ($query) use ($dia, $horaCompleta) {
                $query->where('dia', $dia)
                    ->where('horaInicio', '<=', $horaCompleta)
                    ->where('horaFin', '>', $horaCompleta);
            })
            ->whereDoesntHave('agendas', function ($query) use ($fecha, $hora, $agendaId) {
                $query->where('fecha', $fecha)
                    ->whereTime('hora', '=', $hora)
                    ->where('estado', '!=', 'cancelada')
                    ->when($agendaId, fn ($subquery) => $subquery->where('id', '!=', $agendaId));
            })
            ->with(['turnos' => fn ($query) => $query->where('dia', $dia)
                ->where('horaInicio', '<=', $horaCompleta)
                ->where('horaFin', '>', $horaCompleta)])
            ->orderBy('nombre')
            ->get();
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
