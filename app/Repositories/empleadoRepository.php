<?php

namespace App\Repositories;

use App\Models\empleado;
use App\Models\agenda;
use App\Models\turno;
use App\Support\TextoBusqueda;

class empleadoRepository
{
    public function listarTodo()
    {
        return empleado::query()
            ->select(['id', 'nombre', 'apellido'])
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(empleado::query()->select([
            'id', 'nombre', 'apellido', 'identificacion', 'telefono', 'rh', 'eps', 'estado',
        ]), $texto, fn ($filtros, $termino) => $filtros
                ->where('nombre', 'like', $termino)
                ->orWhere('apellido', 'like', $termino)
                ->orWhere('identificacion', 'like', $termino)
                ->orWhere('telefono', 'like', $termino)
                ->orWhere('rh', 'like', $termino)
                ->orWhere('eps', 'like', $termino))
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(10)
            ->withQueryString();
    }

    public function disponiblesEnHorario(string $fecha, string $hora, string $dia, ?int $agendaId = null)
    {
        $horaCompleta = $hora . ':00';

        return empleado::query()
            ->select(['id', 'nombre', 'apellido'])
            ->where('estado', true)
            ->whereHas('turnos', function ($query) use ($dia, $horaCompleta) {
                $query->where('dia', $dia)
                    ->where('horaInicio', '<=', $horaCompleta)
                    ->where('horaFin', '>', $horaCompleta);
            })
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->where('estado', $filtros['estado'] === 'activo'))
            ->whereDoesntHave('agendas', function ($query) use ($fecha, $hora, $agendaId) {
                $query->where('fecha', $fecha)
                    ->whereTime('hora', '=', $hora)
                    ->where('estado', '!=', 'cancelada')
                    ->when($agendaId, fn ($subquery) => $subquery->where('id', '!=', $agendaId));
            })
            ->with(['turnos' => fn ($query) => $query
                ->select(['id', 'empleado_id', 'dia', 'horaInicio', 'horaFin'])
                ->where('dia', $dia)
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
