<?php

namespace App\Repositories;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\pago;

class AgendaRepository
{
    public function getAll()
    {
    return agenda::with(['empleado', 'cliente', 'vehiculo.tipoVehiculo'])
        ->withSum('agendaServicios', 'Precio')
        ->get();
    }

    public function keep(array $datos)
    {
        return agenda::create($datos);
    }

    public function findById(int $id)
    {
        return agenda::findOrFail($id);
    }

    public function tipoVehiculoDeAgenda(int $agendaId): int
    {
        $agenda = agenda::with('vehiculo')->findOrFail($agendaId);

        if (!$agenda->vehiculo) {
            throw new \RuntimeException('La cita no tiene un vehículo asociado.');
        }

        return (int) $agenda->vehiculo->tipoVehiculoId;
    }

    public function vehiculoOcupado(int $vehiculoId, string $fecha, string $hora, ?int $agendaId = null): bool
    {
        return agenda::where('vehiculoId', $vehiculoId)
            ->where('fecha', $fecha)
            ->whereTime('hora', '=', $hora)
            ->where('estado', '!=', 'cancelada')
            ->when($agendaId, fn ($query) => $query->where('id', '!=', $agendaId))
            ->exists();
    }

    public function empleadoActivo(int $empleadoId): bool
    {
        return \App\Models\empleado::whereKey($empleadoId)->where('estado', true)->exists();
    }

    public function empleadoTieneTurno(int $empleadoId, string $dia, string $hora): bool
    {
        return \App\Models\turno::where('empleado_id', $empleadoId)
            ->where('dia', $dia)
            ->where('horaInicio', '<=', $hora)
            ->where('horaFin', '>', $hora)
            ->exists();
    }

    public function empleadoOcupado(int $empleadoId, string $fecha, string $hora, ?int $agendaId = null): bool
    {
        return agenda::where('empleadoId', $empleadoId)
            ->where('fecha', $fecha)
            ->whereTime('hora', '=', $hora)
            ->where('estado', '!=', 'cancelada')
            ->when($agendaId, fn ($query) => $query->where('id', '!=', $agendaId))
            ->exists();
    }

    public function update(int $id, array $datos)
    {
        $agenda = agenda::findOrFail($id);
        $agenda->update($datos);
        return $agenda;
    }

    public function delete(int $id)
    {
        return agenda::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return agendaServicios::where('agendaId', $id)->exists()
            || pago::where('agendaId', $id)->exists();
    }
}
