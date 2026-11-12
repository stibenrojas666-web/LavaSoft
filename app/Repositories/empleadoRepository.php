<?php

namespace App\Repositories;

use App\Repositories\Contracts\EmpleadoRepositoryInterface;

use App\Models\Empleado;
use App\Models\Agenda;
use App\Models\Turno;
use App\Support\TextoBusqueda;

class EmpleadoRepository implements EmpleadoRepositoryInterface {
    public function listarTodo()
    {
        return Empleado::query()->select(['id', 'nombre', 'apellido'])->orderBy('nombre')->orderBy('apellido')->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(Empleado::query()->select([
        'id', 'nombre', 'apellido', 'identificacion', 'telefono', 'rh', 'eps', 'estado','porcentajeComision',]), $texto, fn ($filtros, $termino) => $filtros
            ->where('nombre', 'like', $termino)
            ->orWhere('apellido', 'like', $termino)
            ->orWhere('identificacion', 'like', $termino)
            ->orWhere('telefono', 'like', $termino)
            ->orWhere('rh', 'like', $termino)
            ->orWhere('eps', 'like', $termino))
        ->when(
            ($filtros['estado'] ?? '') !== '',
            fn ($query) => $query->where('estado', $filtros['estado'] === 'activo')
        )
        ->orderBy('nombre')
        ->orderBy('apellido')
        ->paginate(10)
        ->withQueryString();
    }

    public function candidatosConTurno(string $fecha, string $dia, string $horaInicio, string $horaFin, ?int $agendaId = null)
    {
        return Empleado::query()
            ->select(['id', 'nombre', 'apellido'])
            ->where('estado', true)
            ->whereHas('turnos', function ($query) use ($dia, $horaInicio, $horaFin) {
                $query->where('dia', $dia)
                    ->where('horaInicio', '<=', $horaInicio)
                    ->where('horaFin', '>=', $horaFin);
            })
            ->with([
                'turnos' => function ($query) use ($dia, $horaInicio, $horaFin) {
                    $query->select([
                        'id',
                        'empleado_id',
                        'dia',
                        'horaInicio',
                        'horaFin',
                        'jornada',
                    ])
                    ->where('dia', $dia)
                    ->where('horaInicio', '<=', $horaInicio)
                    ->where('horaFin', '>=', $horaFin);
                },
                'agendas' => function ($query) use ($fecha, $agendaId) {
                    $query->select([
                        'id',
                        'empleadoId',
                        'fecha',
                        'hora',
                        'estado',
                    ])
                    ->whereDate('fecha', $fecha)
                    ->where('estado', '!=', 'cancelada')
                    ->when(
                        $agendaId,
                        fn ($subquery) => $subquery->where('id', '!=', $agendaId)
                    )
                    ->with('agendaServicios:id,agendaId,duracionMinutos');
                },
            ])
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();
    }

    public function guardar(array $datos)
    {
        return Empleado::create($datos);
    }

    public function eliminar(int $id)
    {
        return Empleado::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return Agenda::where('empleadoId', $id)->exists()
            || Turno::where('empleado_id', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return Empleado::findOrFail($id);
    }

    public function buscarParaPago(int $id): ?Empleado
    {
        return Empleado::query()->select(['id', 'porcentajeComision'])->find($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($datos);
        return $empleado;
    }
}
