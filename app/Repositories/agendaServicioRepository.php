<?php

namespace App\Repositories;

use App\Models\agendaServicios;

class AgendaServicioRepository
{
    public function getAll()
    {
        return agendaServicios::with(['agenda.cliente', 'servicio'])->get();
    }

    public function keep(array $datos)
    {
        return agendaServicios::create($datos);
    }

    public function findById(int $id)
    {
        return agendaServicios::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $agendaServicio = agendaServicios::findOrFail($id);
        $agendaServicio->update($datos);
        return $agendaServicio;
    }

    public function delete(int $id)
    {
        return agendaServicios::destroy($id);
    }
}