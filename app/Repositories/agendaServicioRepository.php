<?php

namespace App\Repositories;

use App\Models\agendaServicios;
use App\Models\pago;
use App\Support\TextoBusqueda;

class AgendaServicioRepository
{
    public function getAll()
    {
        return agendaServicios::with(['agenda.cliente', 'vehiculo', 'servicio'])->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(agendaServicios::with(['agenda.cliente', 'vehiculo', 'servicio']), $texto, fn ($consulta, $termino) => $consulta
                ->where('Precio', 'like', $termino)
                ->orWhereHas('agenda', fn ($agenda) => $agenda->where('id', 'like', $termino)
                    ->orWhere('fecha', 'like', $termino)
                    ->orWhereHas('cliente', fn ($cliente) => $cliente->where('nombreCliente', 'like', $termino)
                        ->orWhere('apellidoCliente', 'like', $termino)))
                ->orWhereHas('vehiculo', fn ($vehiculo) => $vehiculo->where('placa', 'like', $termino))
                ->orWhereHas('servicio', fn ($servicio) => $servicio->where('nombre', 'like', $termino)))
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->whereHas('agenda', fn ($agenda) => $agenda->where('estado', $filtros['estado'])))
            ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereHas('agenda', fn ($agenda) => $agenda->whereDate('fecha', '>=', $filtros['desde'])))
            ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereHas('agenda', fn ($agenda) => $agenda->whereDate('fecha', '<=', $filtros['hasta'])))
            ->latest('id')
            ->paginate(12)
            ->withQueryString();
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

    public function agendaTienePago(int $agendaId): bool
    {
        return pago::where('agendaId', $agendaId)->exists();
    }

    public function buscarAgenda(int $agendaId)
    {
        return \App\Models\agenda::with('cliente')->findOrFail($agendaId);
    }
}
