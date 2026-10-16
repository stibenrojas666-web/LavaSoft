<?php

namespace App\Repositories;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\pago;

class AgendaRepository
{
    public function getAll()
    {
        return agenda::with(['empleado', 'cliente', 'vehiculo.tipoVehiculo'])->get();
    }

    public function keep(array $datos)
    {
        return agenda::create($datos);
    }

    public function findById(int $id)
    {
        return agenda::findOrFail($id);
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
